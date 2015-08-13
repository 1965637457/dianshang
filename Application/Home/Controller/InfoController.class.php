<?php
namespace Home\Controller;
class InfoController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_light_info');
        $this->assign('top_banner', $this->_getAdvert(10, true));
    }
    public function index(){
        $this->_getFirstArticle();
        
        $this->display('detail');
    }
    
    public function _empty($action){
        
        $this->_getRewriteArticle($action);
        
        $this->display('detail');
    }

    public function detail(){
        $this->_getArticleById();
        
        $this->display('detail');
    }
    public function downloads(){
        $this->_getRewriteArticle(ACTION_NAME);
        
        $_M = M('Download');
        $condition = array('status'=>1);
        $count = $_M->where($condition)->count();
        $_P = new \Think\Page($count, 10);
        $list = $_M->where($condition)->order('id desc')->limit($_P->firstRow.','.$_P->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
        
        $this->display();
    }
    
    public function sitemap(){
        $this->_getRewriteArticle('/sitemap');
        
        $this->display();
    }
    
    
    public function search(){
        $this->_getRewriteArticle('/search');
        

        $kw = I('get.kw', '', 'trim');
        $this->assign('kw', $kw);
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
                FROM __NEWS__
                WHERE {$where}

                UNION ALL

                SELECT id
                FROM __SERVICE__
                WHERE {$where}

                UNION ALL

                SELECT id
                FROM __RESOURCE__
                WHERE {$where}

                UNION ALL

                SELECT id
                FROM __QUALITY__
                WHERE {$where}

                UNION ALL

                SELECT id
                FROM __TECHNOLOGY__
                WHERE {$where}

                UNION ALL

                SELECT id
                FROM __PRODUCT__
                WHERE status = 1 AND (`title` like '%".addslashes($kw)."%' OR `short_desc` like '%".addslashes($kw)."%')
            ) as a
        ");
        $count = $result[0]['total'];
        $_P = new \Think\Page($count, 5);
        $list = $_M->query("
            SELECT id,title,content,rewriteuri,'About' as controller,update_time
            FROM __ABOUT__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Quality' as controller,update_time
            FROM __QUALITY__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Resource' as controller,update_time
            FROM __RESOURCE__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Service' as controller,update_time
            FROM __SERVICE__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,rewriteuri,'Technology' as controller,update_time
            FROM __TECHNOLOGY__
            WHERE {$where}

            UNION ALL

            SELECT id,title,content,'' as rewriteuri,'News' as controller,update_time
            FROM __NEWS__
            WHERE {$where}
                
            UNION ALL

            SELECT id,title,short_desc as content,rewriteuri,'Product' as controller,update_time
            FROM __PRODUCT__
            WHERE status = 1 AND (`title` like '%".addslashes($kw)."%' OR `short_desc` like '%".addslashes($kw)."%')

            ORDER BY update_time desc
            LIMIT {$_P->firstRow} , {$_P->listRows}
        ");
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
        
        $this->display();
    }
}