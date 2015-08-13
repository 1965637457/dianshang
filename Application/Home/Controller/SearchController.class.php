<?php
namespace Home\Controller;
class SearchController extends CommonController{

    public function index(){
        $type = I('get.type', 0, 'intval');
        $kw = I('get.kw', '', 'trim');
        switch ($type) {
            case 1:
                $this->redirect('info?kw='.$kw);
                break;
            case 2:
                $this->redirect('press?kw='.$kw);
                break;

            default:
                break;
        }
    }
    public function info(){
        $kw = I('get.kw', '', 'trim');
        $this->assign('kw', $kw);
        $condition = array(
            'status' => 1
        );
        $map = array(
            'title' => array('like',"%{$kw}%"),
            'content' => array('like',"%{$kw}%"),
            '_logic' => 'or'
        );
        $condition['_complex'] = $map;
        $_M = M();
        $where = "status = 1 AND (`title` like '%".addslashes($kw)."%' OR `content` like '%".addslashes($kw)."%')";
        $result = $_M->query("
            SELECT COUNT(*) as total
            FROM (
                SELECT id
                FROM __ABOUT__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __EXHIBIT__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __ACTIVITY__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __SERVICE__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __PRESS__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __INFO__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __VISITOR__
                WHERE {$where}
            ) as a
        ");
        $count = $result[0]['total'];
        $_P = new \Think\Page($count, 5);
        $list = $_M->query("
            SELECT id,title,content,rewriteuri,'About' as controller,update_time
            FROM __ABOUT__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Exhibit' as controller,update_time
            FROM __EXHIBIT__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Activity' as controller,update_time
            FROM __ACTIVITY__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Service' as controller,update_time
            FROM __SERVICE__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Press' as controller,update_time
            FROM __PRESS__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Info' as controller,update_time
            FROM __INFO__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Visitor' as controller,update_time
            FROM __VISITOR__
            WHERE {$where}
            
            ORDER BY update_time desc
            LIMIT {$_P->firstRow} , {$_P->listRows}
        ");
        $this->assign('total_infos', $count);
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
        //统计相关新闻数量
        $result_news = $_M->query("
            SELECT COUNT(*) as total
            FROM (
                SELECT id
                FROM __MEDIA__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __INDUSTRY__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __NEWS__
                WHERE {$where}
            ) as a
        ");
        $this->assign('total_news', $result_news[0]['total']);
        
        $this->display();
    }
    public function press(){
        $kw = I('get.kw', '', 'trim');
        $this->assign('kw', $kw);
        $condition = array(
            'status' => 1
        );
        $map = array(
            'title' => array('like',"%{$kw}%"),
            'content' => array('like',"%{$kw}%"),
            '_logic' => 'or'
        );
        $condition['_complex'] = $map;
        $_M = M();
        $where = "status = 1 AND (`title` like '%".addslashes($kw)."%' OR `content` like '%".addslashes($kw)."%')";
        $result = $_M->query("
            SELECT COUNT(*) as total
            FROM (
                SELECT id
                FROM __MEDIA__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __INDUSTRY__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __NEWS__
                WHERE {$where}
            ) as a
        ");
        $count = $result[0]['total'];
        $_P = new \Think\Page($count, 5);
        $list = $_M->query("
            SELECT id,title,content,'media' as action,publish_time
            FROM __MEDIA__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,'industry' as action,publish_time
            FROM __INDUSTRY__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,'news' as action,publish_time
            FROM __NEWS__
            WHERE {$where}
            
            ORDER BY publish_time desc
            LIMIT {$_P->firstRow} , {$_P->listRows}
        ");
        $this->assign('total_news', $count);
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
        //统计相关新闻数量
        $result_info = $_M->query("
            SELECT COUNT(*) as total
            FROM (
                SELECT id
                FROM __ABOUT__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __EXHIBIT__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __ACTIVITY__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __SERVICE__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __PRESS__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __INFO__
                WHERE {$where}

                UNION ALL
                
                SELECT id
                FROM __VISITOR__
                WHERE {$where}
            ) as a
        ");
        $this->assign('total_infos', $result_info[0]['total']);
        
        
        $this->display();
    }
}

?>
