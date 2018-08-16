<?php

namespace App\Console\Commands;

use App;
use Workerman\Worker;
use Illuminate\Console\Command;
use Workerman\Lib\Timer;
use Workerman\MySQL\Connection;



class Workerman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman:command {action} {--daemonize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workerman Server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        define('HEARTBEAT_TIME', 55);

//        Worker::$logFile = '/tmp/workerman.log'; //用来指定workerman日志文件位置。

        $ws = new Worker("websocket://0.0.0.0:9011");

        $ws->name = 'yy';//设置当前Worker实例的名称，方便运行status命令时识别进程。不设置时默认为none。

        $ws->count = 1;

        $ws->onWorkerStart = function($worker)
        {
            // 将db实例存储在全局变量中(也可以存储在某类的静态成员中)
            global $db;
            $db = new Connection('127.0.0.1', '3306', 'root', '', 'admin');
            echo "mysql has connected\n";
        };


        $ws->onConnect = function($connection)
        {
            echo "new connection\n";
        };

        $ws->onMessage = function($connection, $data)
        {
            global $worker,$db;
            $data = json_decode($data , true);
            switch ($data['type']){
                case 'login':
                    //格式['type'=> 'login' , 'uid'=>]
                    // 判断当前客户端是否已经验证,即是否设置了uid
                    if(!isset($connection->uid)) {
                        // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
                        $connection->uid = $data['uid'];
                        // 保存uid到connection的映射，这样可以方便的通过uid查找connection，实现针对特定uid推送数据
                        $worker->uidConnections[$connection->uid] = $connection;
                        $redata["msg"] = 'login success, your uid is ' . $connection->uid;
                        return $connection->send(json_encode($redata));
                    }
                    break;

                case 'chat' :
                    //格式['type'=>'chat','from_uid'=>,'to_uid' =>,'msg'=>,'msgtype'=>,'flag'=>]
                    //添加数据到数据库
                    if($data["msg"]){
                        $insertData["create_time"] = $insertData["update_time"] = time();
                        $insertData["from_userid"] = $data["from_uid"];
                        $insertData["to_userid"] = $data["to_uid"];
                        $insertData["msg"] = $data["msg"];
                        $insertData["msg_type"] = $data["msgtype"];
                        $insertData["flag"] = $data["flag"];
                        $insert_id = $db->insert('cmf_chat')->cols($insertData)->query();
                    }

                    if(isset($worker->uidConnections[$data['to_uid']])) {
                        $connection = $worker->uidConnections[$data['to_uid']];
                        return $connection->send(json_encode(array_merge(getChatInfo($insert_id) , ['type'=>'chat'])));
                    }
                    break;
            }

        };

        $ws->onClose = function($connection)
        {
            echo "Connection closed\n";
        };


        //获取用户聊天信息
        function getChatInfo($chatid){
            global $db;
            $list = $db->select("
                            cmf_chat.create_time ,
                            cmf_userinfos.headimage,
                            nickname,
                            headimage,
                            cmf_userinfos.flag as userfalg,
                            cmf_chat.flag,
                            cmf_chat.msg,
                            msg_type,
                            cmf_chat.from_userid as userid
                            ")
                ->from('cmf_chat')
                ->innerJoin('cmf_userinfos','cmf_userinfos.id = cmf_chat.from_userid')
                ->where("cmf_chat.id=$chatid")
                ->limit(1)
                ->query();
            $user = $list[0];
            $user["create_time"] = date("Y-m-d H:i:s" , $user["create_time"]);
            if($user["userfalg"] == 1){
                $user["headimage"] = 'http://www.blog.cn/upload/'. $user["headimage"];
            }else{
                $user["headimage"] = 'http://www.think.cn/upload/'. $user["headimage"];
            }
            return $user;
        }

        // Run worker
        Worker::runAll();
    }
}


