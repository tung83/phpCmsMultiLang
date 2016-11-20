<?php
class promotion extends base{
    private $news_cateId;
    function __construct($db,$lang='vi'){
        parent::__construct($db,13,'news',$lang);
        $this->news_cateId = 16;
    }
    function promotion_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        return '
            <div class="row news-item wow fadeInLeft animated" data-wow-duration="1000ms" data-wow-delay="10ms">
                <div class="col-xs-3">
                    <a href="'.$lnk.'" class="about-item ">
                        <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
                    </a>     
                </div>
                <div class="col-xs-7">
                    <a href="'.$lnk.'" class="about-item clearfix">
                        <p class="news-title">'.$item['title'].'</p>
                    </a>
                    <p class="news-date"><i> '.date("d/m/Y",$item['date']).'</i></p>
                    <div class="news-sum">
                        <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
                    </div>
                </div>
            </div>
            <hr/>';
    }
    function promotion_cate(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        $this->db->where('pId',$this->news_cateId);
        $this->db_orderBy();
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('news',$page);
        $count=$this->db->totalCount;
        $str.='<div class="news-list">';
        if($count>0){
            foreach($list as $item){
                $str.=$this->promotion_item($item);
            }
        }        
        $str.='</div>';
        
        $pg=new Pagination(array('limit'=>pd_lim,'count'=>$count,'page'=>$page,'type'=>0));  
        if($pId==0){
            $pg->set_url(array('def'=>myWeb.$this->lang.'/'.$this->view,'url'=>myWeb.$this->lang.'/'.$this->view.'/page[p]'));
        }else{
            $cate=$this->db->where('id',$pId)->getOne('news_cate','id,title');       
            $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = $pg->defaultUrl.'/page[p]';
        }
        $str.= '<div class="pagination-wrapper"> <div class="text-center">'.$pg->process().'</div></div>';
        $this->paging_shown = ($pg->paginationTotalpages > 0);
        return $str;
    }
    function promotion_one($id=1){
        $item=$this->db->where('id',$id)->getOne('news');
        $title=$this->lang=='vi'?$item['title']:$item['e_title'];
        $content=$this->lang=='vi'?$item['content']:$item['e_content'];
        return  
            '<article>
                <div class="text-center">
                    <h2 class="page-title">'.$title.'</h2>
                </div>
                <p>'.$content.'</p>
            </article>';                        
    }

}

