<?php
class base{
    protected $db,$lang,$view,$title,$db_name,$db_cate_name,$paging_shown;
    protected function __construct($db,$id,$db_name,$lang='vi',$table='menu'){
        $this->db_name = $db_name;
        $this->db_cate_name = $db_name.'_cate';
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',$id);
        $item=$db->getOne($table);
        switch($this->lang){
            case 'en':
                $this->view=$item['e_view'];
                $this->title=$item['e_title'];
                break;
            default:
                $this->view=$item['view'];
                $this->title=$item['title'];
                break;
        }
    }
    
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
            <li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i> Trang chủ</a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        $str.='
        </ul>
        </div>';
        return $str;
    }
    
    function breadcrumb_cate_lev1(){
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
            <li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i> Trang chủ</a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){      
            $this->db->reset();
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne($this->db_name,'id,title,pId'); 
            
            $this->db->reset();
            $cate=$this->db->where('id',$item['pId'])->getOne($this->db_cate_name,'id,title');
            $str.='<li><a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>';
                       
            $title = ($this->lang == 'en') ? $item['e_title'] : $item['title'];
            $str.='<li><a href="#">'.$title.'</a></li>';
        }elseif(isset($_GET['pId'])){                    
            $this->db->reset();
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne($this->db_cate_name,'id,title');
            $str.=' <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    
    
    function breadcrumb_with_Id(){
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
            <li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i> Trang chủ</a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){      
            $this->db->reset();
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne($this->db_name,'id,title,pId');                  
            $title = ($this->lang == 'en') ? $item['e_title'] : $item['title'];
            $str.='<li><a href="#">'.$title.'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    
    function top_content($fade_wow='wow fadeInDown animated'){
        return '  
        <section id="'.$this->db_name.'">
            <div class="container">
                <div class="row '.$this->db_name.'-box">
                    <div class="row '.$fade_wow.'" >
                        <div class="col-xs-6">
                            <div class="title-head">
                                <span>'
                                    .$this->title.' 
                                </span>
                            </div>
                        </div> 
                        <div class="col-xs-12">';
    }
    function bottom_content(){
        $has_paging = ($this->paging_shown)? "has-paging":"";
        return '        </div>
                    </div>
                </div>
                '.shadowBottomDent().' 
                <div class="'.$has_paging.'">
                </div>
            </div>
        </section>';
    }
    protected function check_pId(){
        if(isset($_GET['pId'])){
            $pId=intval($_GET['pId']);
        }elseif(isset($_GET['id'])){
            $item=$this->db->where('id',intval($_GET['id']))->getOne($this->db_cate_name,'pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    protected function db_orderBy(){        
        $this->db->orderBy('ind','ASC');
        $this->db->orderBy('id');
    }
}