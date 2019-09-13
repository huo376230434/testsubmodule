<?php
namespace App\Lib\Common\Database;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 15:56
 */
class MysqlMaintenance{

    protected $database_name;
    protected $username;
    protected $password;
    protected $save_path;

    public function __construct($mysql_conf=[])
    {
        !$mysql_conf && $mysql_conf = config("database.connections.mysql");
        $this->database_name =$mysql_conf['database'];
        $this->username = $mysql_conf['username'] ;
        $this->password =$mysql_conf['password'] ;

        $this->save_path =isset($mysql_conf['database_backup_path']) ? str_finish($mysql_conf['database_backup_path'],"/") : storage_path("mysql_backup/");
//        if(!$this->save_path){
//            throw new \Exception("配置database.connections.mysql.database_backup_path不存在");
//        }
        if(!is_dir($this->save_path)) {
            mkdir($this->save_path);
        }
        //将密码中的!字符转为\!
        $this->password = str_replace("!","\!",$this->password);
    }

    public function makeSavePathWritable()
    {

        $command = "chmod -R 777 " . $this->save_path;
        exec($command);
    }




    public  function backup($only_structure=false,$toCloud=false)
    {

//执行备份命令
        $name = $this->database_name.'_'.date('Y_m_d_H_i_s').'.sql';
        $extra_option = "";
        if($only_structure){
            $extra_option = ' -d ';
            $name = 'only_structure'.$name;
        }
        $backup_command = 'mysqldump '.$extra_option.' -u'.$this->username.' -p'.$this->password.' '.$this->database_name.' > '.$this->save_path.$name;
        exec($backup_command);
        //将文件夹可写，方便后台删除
        $this->makeSavePathWritable();
        if($toCloud){

//            dispatch(new UploadObjectToCloud(['path'=> $save_path,'name' => $name]));
        }
        return ["name" => $name,'full_name' => $this->save_path.$name];


    }


    public  function recover($name,$database_name='')
    {
        if ($database_name) {
            $this->database_name = $database_name;
        }
        $backup_command = 'mysql -u'.$this->username.' -p'.$this->password.' '.$this->database_name.' < '.$name;
//        dump($backup_command);
        exec($backup_command);
    }


    public function backupProcedures()
    {
        $name = date("Ymd-H:i").'procedures.sql';
        $path = $this->save_path . $name;
        $comand = 'mysqldump -u'.$this->username.' -p'.$this->password.' '.$this->database_name.' -ntd -R > '.$path;
        exec($comand);

        //将文件夹可写，方便后台删除
        $this->makeSavePathWritable();
//        将文件中的权限相关删除,方便直接用

        $content = file_get_contents($path);
        $content =  str_replace('DEFINER=`d5pay`@`%`', '', $content);

        file_put_contents($path, $content);
    }


}
