<?php
class about extends base{
    function __construct($db,$lang='vi'){
        parent::__construct($db,2,'about',$lang);
    }
    function ind_about(){
        $this->db->where('active',1);
        $this->db->orderBy('id','ASC');
        $item=$this->db->getOne('about');
        $lnk=myWeb.$this->lang.'/'.$this->view;
        $title=$this->lang=='vi'?$item['title']:$item['e_title'];
        $sum=$this->lang=='vi'?$item['sum']:$item['e_sum'];  
        $str='
        <div class="row ind-about wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="10ms">
            <div class="col-xs-4"> 
                <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            </div>
            <div class="col-xs-8">
                <h2 class="title">'.$title.'</h2>
                <p>'.common::str_cut($sum,400).'</p>
                <p class="text-right more">
                    <a href="'.myWeb.$this->lang.'/'.$this->view.'">'.more.'</a>
                </p>
            </div>
        </div>';
        return $str;
    }
    
    function about_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('about',$page);
        $count=$this->db->totalCount;
        foreach($list as $item){
            $str.=$this->about_item($item);
        }
        
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));
        $pg->set_url(array('def'=>myWeb.$this->view,'url'=>myWeb.'[p]/'.$this->view));

        $str.= '<div class="pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function about_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about-item clearfix">
            <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            <div>
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>';
        return $str;
    }
    
    function about_one(){
        $id=1;
        $item=$this->db->where('id',$id)->getOne('about');
        $title=$this->lang=='vi'?$item['title']:$item['e_title'];
        $content=$this->lang=='vi'?$item['content']:$item['e_content'];
        return '  
        <section id="about-us">
            <div class="container">
                <div class="row about-us-box">
                    <div class="row wow fadeInDown animated" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="col-xs-6">
                            <div class="title-head">
                                <span>'
                                    .$this->title.' 
                                </span>
                            </div>
                        </div> 
                        <div class="col-md-12">
                            <article>
                                <div class="text-center">
                                    <h2 class="page-title">'.$title.'</h2>
                                </div>
                                <p>'.$content.'</p>
                            </article>
                        </div>
                    </div>
                </div>
                '.shadowBottomDent().' 
            </div>
        </section>';
    }
}


?>
