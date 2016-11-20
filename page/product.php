<?php
class product extends base{
    function __construct($db,$lang='vi'){        
        parent::__construct($db,5,'product',$lang);
    }
    function ind_product(){ 
        $str.='
        <section class="row ind-product"> 
            <div class="col-xs-6">
                <div class="title-head">
                    <span>'
                        .$this->title.' 
                    </span>
                </div>
            </div> 
            <div class="col-xs-6 text-right more">
                <a href="'.myWeb.$this->lang.'/'.$this->view.'">'.more.'</a>
            </div>
            <div class="clearfix"></div>';
        $this->db->where('active',1)->where('home',1);
        $this->db_orderBy();
        $list=$this->db->get('product');   
        foreach($list as $item){
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $img=$this->first_image($item['id']);
            $str.='
            <div class="col-xs-3 product-col wow bounceIn animated" data-wow-duration="2s" data-wow-delay="60ms">
                <div class="product-item">
                    <a href="'.$lnk.'">
                        <img src="'.webPath.$img.'" class="img-responsive center-block hvr-grow"/>
                        <p class="text-center">'.$item['title'].'</p>
                    </a>
                </div>
            </div>';
        }
        $str.='
        </section><!--/#partner-->';
        
        return $str;
    }
    function hot_product(){
        $this->db->reset();
        $this->db->where('active',1)->where('home',1);
        $list=$this->db->get('product',null);
        $i=1;
        foreach($list as $item){
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $str.=$this->product_item($item);
            if($i%4==0){
                $str.='
                </div>';
            }
            $i++;
        }   
        if($i%4!=1){
            $str.='
            </div>';
        }
        return $str;
    }
    function product_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
//        $str.='
//        <div class="col-xs-3 wow fadeInLeft product-item text-center" data-wow-duration="2s">
//        <a href="'.$lnk.'">
//			<figure>
//				<img src="'.webPath.'thumb_'.$img.'" alt="'.$item['title'].'" title="'.$item['title'].'" class="img-responsive center-block">
//				<figcaption class="text-center">
//					<h3>'.common::str_cut($item['title'],30).'</h3>
//					<!--span><b>Giá bán:</b> <em>'.number_format($item['price'],0,'.','.').'VNĐ</em></span-->
//				</figcaption>
//			</figure>
//        </a>
//		</div>';
        $str.='
            <div class="col-xs-3 wow fadeIn animated product-col" data-wow-duration="1000ms" data-wow-delay="60ms">
                <div class="product-item">
                    <a href="'.$lnk.'">
                        <img src="'.webPath.$img.'" class="img-responsive center-block hvr-grow"/>
                        <p class="text-center">'.$item['title'].'</p>
                    </a>
                </div>
            </div>';
        return $str;
    }
    function product_list_item($item,$type=1){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        if($type==1){
            $str='
            <div class="col-md-12 col-sm-6 col-md-3 product-item">';    
        }else{
            $str='
            <div class="col-md-12 col-sm-6 col-md-4 product-item">';
        }        
        $str.='
        <a href="'.$lnk.'">
            <div>
                <p>'.($item['price']==0?contact:number_format($item['price'],0,',','.').' VNĐ').'</p>
                <img src="'.$img.'" class="img-responsive" />
                <p>
                    <h2>'.$item['title'].'</h2>
                    <button class="btn btn-default">'.more.'</button>
                </p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function category(){
        $pId=$this->check_pId();
        $this->db->where('active',1);
        $this->db_orderBy();
        $list=$this->db->get('product_cate',null,'id,title');
        $str='
        <div class="row product-category">
        <div class="col-xs-12">';
        foreach($list as $item){
            if($item['id']==$pId){
                $active=' class="active"';
            }else{
                $active='';
            }
            $str.='
            <a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'].'"'.$active.'>
                '.$item['title'].'
            </a>';
        }
        $str.='
        </div>
        </div>';
        return $str;
    }
    function product_cate(){
        $pId = $this->check_pId();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0){
            $this->db->where('pId',$pId);
        }
        $this->db_orderBy();
        $this->db->pageLimit=24;
        $list=$this->db->paginate('product',$page);
        $count=$this->db->totalCount;
        $str.='<div class="product-list">'
                . '<div class="row">';
        if($count>0){
            foreach($list as $item){
                $str.=$this->product_item($item);
            }
        }        
        $str.=      '</div>'
                .'</div>'
                .'<div class="clearfix"></div>';
        
        $pg=new Pagination(array('limit'=>24,'count'=>$count,'page'=>$page,'type'=>0));  
        if($pId==0){
            $pg->set_url(array('def'=>myWeb.$this->lang.'/'.$this->view,'url'=>myWeb.$this->lang.'/'.$this->view.'/page[p]'));
        }else{
            $cate=$this->db->where('id',$pId)->getOne('product_cate','id,title');       
            $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = $pg->defaultUrl.'/page[p]';
        }
        $str.= '<div class="pagination-wrapper"> <div class="text-center">'.$pg->process().'</div></div>';
        $this->paging_shown = ($pg->paginationTotalpages > 0);
        return $str;
    }
    
    function product_search(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        $this->db->where('title','%'.$_GET['hint'].'%', 'like');        
        $this->db_orderBy();
        $this->db->pageLimit=24;
        $list=$this->db->paginate('product',$page);        
        $count=$this->db->totalCount;
       $str.='<div class="alert alert-success"><i class="icon fa fa-check"></i>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                Có '.$count. ' kết quả với từ khoá <b>"'.$_GET['hint'].'"</b>
              </div>';
        $str.='<div class="product-list">'
                . '<div class="row">';
        if($count>0){
            foreach($list as $item){
                $str.=$this->product_item($item);
            }
        }        
        $str.=      '</div>'
                .'</div>'
                .'<div class="clearfix"></div>';
        
        $pg=new Pagination(array('limit'=>24,'count'=>$count,'page'=>$page,'type'=>0));  
             
            $pg->defaultUrl = myWeb.$this->lang.'/'.search_view.'/'.$_GET['hint'];
            $pg->paginationUrl = myWeb.$this->lang.'/'.search_view.'/page[p]'.'/'.$_GET['hint'];
        
        $str.= '<div class="pagination-wrapper"> <div class="text-center">'.$pg->process().'</div></div>';
        $this->paging_shown = ($pg->paginationTotalpages > 0);
        return $str;
    }
    function product_one($id){
        $this->db->where('id',$id);
        $item=$this->db->getOne('product','id,price,price_reduce,title,content,pId,feature,manual,promotion,video');
        $this->db->where('pId',$item['pId'])->where('id',$item['id'],'<>')->where('active',1)->orderBy('rand()');
        $list=$this->db->get('product');
        $lnk=domain.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="row product-detail clearfix">
            
            <div class="col-xs-12">
            <div class="col-xs-5">
                '.$this->product_image_show($item['id']).'
            </div>
                <article class="product-one">
                <h1>'.$item['title'].'</h1>
                <!--b>Giá Bán Lẻ: <em>'.number_format($item['price'],0,',','.').'VNĐ</em></b-->
                <!--form action="javascript:add_cart('.$item['id'].',1)">
                    <button class="btn btn-default"><i class="fa fa-shopping-cart"></i> Mua Hàng</button>
                </form-->
                <p>'.$item['feature'].'</p>
                </article>
            </div>
        </div>                   
        <div>
            <div id="tabs" class="tabs">
                <ul>
                    <li><a href="#tabs-1"><i class="fa fa-file-text-o"></i> MÔ TẢ CHI TIẾT</a></li>
                    <li><a href="#tabs-2"><i class="fa fa-list-alt"></i> THÔNG SỐ KỸ THUẬT</a></li>
                    <li><a href="#tabs-3"><i class="fa fa-pencil-square-o"></i> GHI CHÚ</a></li>
                    <!--li><a href="#tabs-4">BÌNH LUẬN</a></li-->
                </ul>
                <div id="tabs-1">
                    <article>
                        <p>'.$item['content'].'</p>
                    </article>
                </div>
                <div id="tabs-2">
                    <article>
                        <p>'.$item['detail'].'</p>
                    </article>
                </div>
                <div id="tabs-3">
                    <article>
                        <p>'.$item['manual'].'</p>
                    </article>
                </div>
                <!--div id="tabs-4">
                    <div class="fb-comments" data-width="100%" data-href="'.$lnk.'" data-numposts="5"></div>
                </div-->
            </div>       
        </div>';
        if(count($list)>0){
            $str.='
            <h3 class="small-title">
                    SẢN PHẨM CÙNG LOẠI
            </h3>';
            $str.='<div class="slick product_list clearfix">';

            foreach($list as $item){                
                $str.=$this->product_item($item);                
            }  
            $str.='</div>';  
        }        
        return $str;
    }
    function first_image($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id);
        $this->db_orderBy();
        $img=$this->db->getOne('product_image','img');
        return $img['img'];
    }
    function product_image_show($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id);
        $this->db_orderBy();
        $list=$this->db->get('product_image');
        $temp=$tmp='';
        foreach($list as $item){
            $temp.='
            <li>
                <a href="'.webPath.$item['img'].'" >
                    <img src="'.webPath.$item['img'].'" alt="" title="" class=""/>
                </a>
            </li>';
            $tmp.='
            <li>
                <img src="'.webPath.'thumb_'.$item['img'].'" alt="" title=""/>
            </li>';
        }
        $str.='
        <!-- Place somewhere in the <body> of your page -->
        <div id="image-slider" class="flexslider">
          <ul class="slides popup-gallery">
            '.$temp.'
          </ul>
        </div>
        <div id="carousel" class="flexslider" style="margin-top:-50px;margin-bottom:10px">
          <ul class="slides">
            '.$tmp.'
          </ul>
        </div>
        <script>
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 80,
            itemMargin: 5,
            asNavFor: "#image-slider"
          });

          $("#image-slider").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
          });
        });
        </script>';
        return $str;
    }

    function product_cate_list(){
        $this->db->reset();
        $this->db->where('active',1);
        $this->db_orderBy();
        $list=$this->db->get($this->db_cate_name);
        $str.='
        <ul class="product-menu">';
        foreach($list as $cate){
            $title=$this->lang=='en'?$cate['e_title']: $cate['title'];
            $str.='
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$cate["id"].'">'.$title.'</a></li>';   
        }
        $str.='
        </ul>';
        return $str;
    }
}
?>