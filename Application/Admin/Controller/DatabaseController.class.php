<?php

namespace Admin\Controller;
use Think\Db;
use Org\Db\Database;
class DatabaseController extends CommonController{
    
    public function index(){
        $Db    = Db::getInstance();
        $list  = $Db->query('SHOW TABLE STATUS');
        $list  = array_map('array_change_key_case', $list);
        $this->assign('list', $list);
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    /**
     * 导出数据
     */
    protected function _handle_export(){
        $tables = I('post.tables');
        if(!empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
            $config = array(
                'path'     => realpath(C('DATA_BACKUP_PATH')).DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );

            //创建备份文件
            $Database = new Database($file, $config);
            if(false === $Database->create()){
                $this->error('初始化失败，备份文件创建失败！');
            }
            
            //备份指定表
            foreach($tables as $table){
                $result = array('start'=>0);
                do{
                    $start = isset($result['start']) ? $result['start'] : $result[0];
                    $result  = $Database->backup($table, $start);
                }while(is_array($result));
                //出错
                if(false === $result){
                    $this->error("表{$table}备份出错！");
                } elseif (0 === $result) { //下一表
                    continue;
                }
            }
            unlink($config['path'] . 'backup.lock');
            $this->success('备份完成！');
        }else{
            $this->error('请选择要备份的数据表！');
        }
    }
    /**
     * 优化数据表
     */
    protected function _handle_optimize(){
        $tables = I('post.tables');
        if($tables){
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        }else{
            $this->error("请指定要优化的表！");
        }
    }
    /**
     * 修复数据表
     */
    protected function _handle_repair() {
        $tables = I('post.tables');
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if($list){
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            } else {
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'修复完成！");
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }
    /**
     * 查看备份数据库
     */
    public function backups(){
        //列出备份文件列表
        $path = realpath(C('DATA_BACKUP_PATH'));
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);

        $list = array();
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime("{$date} {$time}");

                $list["{$date} {$time}"] = $info;
            }
        }
        arsort($list);
        $this->assign('list', $list);
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    /**
     * 恢复数据库
     */
    public function recover($time=0){
        //获取备份文件信息
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        $list  = array();
        if($files){
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
        }
        if(empty($list)){
            $this->error('找不到相应的备份文件');
        }
        ksort($list);

        //检测文件正确性
        $last = end($list);
        if(count($list) === $last[0]){
            foreach($list as $partial){
                $result = array('start'=>0);
                do{
                    $start = $result['start'];
                    $db = new Database($partial, array(
                        'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                        'compress' => $partial[2]));
                    $result = $db->import($start);
                }while(is_array($result));
                if(false === $result){
                    $this->error('还原数据出错！');
                } elseif(0 === $result) { //下一卷
                    continue;
                }
            }
            $this->success('还原完成！');
        } else {
            $this->error('备份文件可能已经损坏，请检查！');
        }
    }
    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function delete($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->success('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
}

?>
