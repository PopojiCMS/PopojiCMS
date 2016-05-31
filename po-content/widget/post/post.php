<?php
/*
 *
 * - PopojiCMS Widget File
 *
 * - File : post.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses di bagian depan untuk widget post.
 * This is a php file for handling front end process for post widget.
 *
*/

/**
 * Memanggil class utama PoTemplate (diharuskan).
 *
 * Call main class PoTemplate (require).
 *
*/
use PoTemplate\Engine;
use PoTemplate\Extension\ExtensionInterface;

/**
 * Mendeklarasikan class widget diharuskan dengan mengimplementasikan class ExtensionInterface (diharuskan).
 *
 * Declaration widget class must with implements ExtensionInterface class (require).
 *
*/
class Post implements ExtensionInterface
{

	/**
	 * Fungsi ini digunakan untuk menginisialisasi class utama (diharuskan).
	 *
	 * This function use to initialize the main class (require).
	 *
	*/
	public function __construct()
	{
		$this->core = new PoCore();
	}

	/**
	 * Fungsi ini digunakan untuk mendaftarkan semua fungsi widget (diharuskan).
	 *
	 * This function use to register all widget function (require).
	 *
	*/
    public function register(Engine $templates)
    {
        $templates->registerFunction('post', [$this, 'getObject']);
    }

	/**
	 * Fungsi ini digunakan untuk menangkap semua fungsi widget (diharuskan).
	 *
	 * This function use to catch all widget function (require).
	 *
	*/
    public function getObject()
    {
        return $this;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post.
	 *
	 * This function use to get list of post.
	 *
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
	public function getPost($limit, $order, $lang)
    {
		$popular = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy('post.id_post '.$order.'')
			->limit($limit)
			->fetchAll();
        return $popular;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berdasarkan id_post.
	 *
	 * This function use to get list of post base on id_post.
	 *
	 * $id_post = integer
	 * $lang = WEB_LANG_ID
	*/
	public function getPostById($id_post, $lang)
    {
		$post = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post.id_post', $id_post)
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->limit(1)
			->fetch();
        return $post;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berdasarkan kategori.
	 *
	 * This function use to get list of post base on category.
	 *
	 * $id_category = integer
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
	public function getPostByCategory($id_category, $limit, $order, $lang)
    {
		$post = array();
		$categorys = $this->core->podb->from('post_category')
			->where('id_category', $this->getCategoryParentTree($id_category))
			->orderBy('id_post_category '.$order.'')
			->limit($limit)
			->fetchAll();
		foreach($categorys as $cat){
			$if_post = $this->getPostById($cat['id_post'], $lang);
			if (!empty($if_post['active'])) {
				$post[] = $this->getPostById($cat['id_post'], $lang);
			}
		}
		return $this->arrayOrderBy($post, 'id_post', SORT_DESC);
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post populer.
	 *
	 * This function use to get list of popular post.
	 *
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
    public function getPopular($limit, $order, $lang)
    {
		$popular = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy('post.hits '.$order.'')
			->limit($limit)
			->fetchAll();
        return $popular;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post terakhir.
	 *
	 * This function use to get list of recent post.
	 *
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
	public function getRecent($limit, $order, $lang)
    {
		$recent = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy('post.id_post '.$order.'')
			->limit($limit)
			->fetchAll();
        return $recent;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post headline.
	 *
	 * This function use to get list of headline post.
	 *
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
	public function getHeadline($limit, $order, $lang)
    {
		$headline = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.headline', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy('post.id_post '.$order.'')
			->limit($limit)
			->fetchAll();
        return $headline;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post terkait.
	 *
	 * This function use to get list of related post.
	 *
	 * $id_post = integer
	 * $tag = array of tag
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $lang = WEB_LANG_ID
	*/
	public function getRelated($id_post, $tags, $limit, $order, $lang)
    {
		$exp_tag  = explode(",", $tags);
		$total = (integer)count($exp_tag);
		$total_tag = $total-1;
		$conditions = array();
		for ($i=0; $i<=$total_tag; $i++){
			$conditions[] = "post.tag LIKE '%".$exp_tag[$i]."%'";
		}
		$orWhere = implode(" OR ", $conditions);
		$related = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post_description.id_language', $lang)
			->where('post.id_post != ?', $id_post)
			->where('('.$orWhere.')')
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy('post.id_post '.$order.'')
			->limit($limit)
			->fetchAll();
        return $related;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post sebelumnya.
	 *
	 * This function use to get list of previous post.
	 *
	 * $id_post = integer
	 * $lang = WEB_LANG_ID
	*/
	public function getPrevPost($id_post, $lang)
    {
		$post = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post.id_post < ?', $id_post)
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->limit(1)
			->fetch();
        return $post;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berikutnya.
	 *
	 * This function use to get list of next post.
	 *
	 * $id_post = integer
	 * $lang = WEB_LANG_ID
	*/
	public function getNextPost($id_post, $lang)
    {
		$post = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post.id_post > ?', $id_post)
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->limit(1)
			->fetch();
        return $post;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar komentar.
	 *
	 * This function use to get list of comment.
	 *
	 * $limit = integer
	 * $order = string ASC or DESC
	*/
	public function getComment($limit, $order)
    {
		$comment = $this->core->podb->from('comment')
			->where('active', 'Y')
			->orderBy('id_comment '.$order.'')
			->limit($limit)
			->fetchAll();
        return $comment;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar komentar berdasarkan id_post.
	 *
	 * This function use to get list of comment base on id_post.
	 *
	 * $id_post = integer
	 * $limit = integer
	 * $order = string ASC or DESC
	 * $page = integer from get active page
	*/
	public function getCommentByPost($id_post, $limit, $order, $page)
    {
		$offset = $this->core->popaging->searchPosition($limit, $page);
		$comment = $this->core->podb->from('comment')
			->where('id_post', $id_post)
			->where('id_parent', '0')
			->where('active', 'Y')
			->orderBy('id_comment '.$order.'')
			->limit($offset.','.$limit)
			->fetchAll();
        return $comment;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar komentar berdasarkan id_parent.
	 *
	 * This function use to get list of comment base on id_parent.
	 *
	 * $id_parent = integer
	 * $limit = integer
	 * $order = string ASC or DESC
	*/
	public function getCommentByParent($id_parent, $limit, $order)
    {
		$comment = $this->core->podb->from('comment')
			->where('id_parent', $id_parent)
			->where('active', 'Y')
			->orderBy('id_comment '.$order.'')
			->limit($limit)
			->fetchAll();
        return $comment;
    }

	/**
	 * Fungsi ini digunakan untuk membuat dan menampilkan komentar pada post.
	 *
	 * This function use to create and generate comment in post.
	 *
	 * $comment = array of comment
	 * $order = string ASC or DESC
	 * $template = array of template
	*/
	public function generateComment($comments, $order, $template)
    {
		$comment_template = '';
		foreach($comments as $comment){
			$datetime = date_create($comment['date'].' '.$comment['time']);
			$comment_template .= str_replace('{$comment_id}', $comment['id_comment'], $template['parent_tag_open']);
			$comment_template_con = str_replace('{$comment_id}', $comment['id_comment'], $template['comment_list']);
			$comment_template_con = str_replace('{$comment_name}', $comment['name'], $comment_template_con);
			$comment_template_con = str_replace('{$comment_avatar}', BASE_URL.'/'.DIR_CON.'/uploads/avatar.jpg', $comment_template_con);
			$comment_template_con = str_replace('{$comment_url}', $this->core->postring->addhttp($comment['url']), $comment_template_con);
			$comment_template_con = str_replace('{$comment_datetime}', date_format($datetime, 'd M Y').' '.date_format($datetime, 'H:i:a'), $comment_template_con);
			$comment_template_con = str_replace('{$comment_content}', nl2br($comment['comment']), $comment_template_con);
			$comment_template .= $comment_template_con;
			$comment_child = $this->core->podb->from('comment')
				->where('id_parent', $comment['id_comment'])
				->where('active', 'Y')
				->orderBy('id_comment '.$order.'')
				->fetchAll();
			if (count($comment_child) > 0) {
				$comment_template .= str_replace('{$comment_id}', $comment['id_comment'], $template['child_tag_open']);
				$comment_template .= $this->generateComment($comment_child, 'DESC', $template);
				$comment_template .= $template['child_tag_close'];
			}
			$comment_template .= $template['parent_tag_close'];
		}
        return $comment_template;
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman galeri
	 *
	 * This function use to create pagination in gallery page.
	 *
	 * $limit = integer
	 * $id_post = integer
	 * $seotitle = string
	 * $page = integer from get active page
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getCommentPaging($limit, $id_post, $seotitle, $page, $type, $prev, $next)
    {
		$totaldata = $this->core->podb->from('comment')->where('id_post', $id_post)->where('id_parent', '0')->where('active', 'Y')->count();
		$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
		$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'detailpost/'.$seotitle, 'page', $type, $prev, $next);
		return $pagination;
	}

	/**
	 * Fungsi ini digunakan untuk menghitung jumlah komentar pada post.
	 *
	 * This function use to count comments total in post.
	 *
	 * $id_post = integer
	*/
	public function getCountComment($id_post)
    {
		$comment = $this->core->podb->from('comment')
			->where('id_post', $id_post)
			->where('active', 'Y')
			->count();
        return $comment;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil data author.
	 *
	 * This function use to get author data.
	 *
	 * $id_user = integer
	*/
	public function getAuthor($id_user)
    {
		$author = $this->core->podb->from('users')
			->select(array('nama_lengkap', 'email', 'no_telp', 'bio', 'picture'))
			->where('id_user', $id_user)
			->limit(1)
			->fetch();
        return $author;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil nama author.
	 *
	 * This function use to get author name.
	 *
	 * $id_user = integer
	*/
	public function getAuthorName($id_user)
    {
		$author = $this->core->podb->from('users')
			->select(array('nama_lengkap', 'email', 'no_telp', 'bio', 'picture'))
			->where('id_user', $id_user)
			->limit(1)
			->fetch();
        return $author['nama_lengkap'];
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar semua tag.
	 *
	 * This function use to get all list of tag.
	 *
	 * $order = string
	 * $limit = integer
	 * $sep = string separator
	 * $link = boolean
	 * $opentag = string
	 * $closetag = string
	 * $class = string
	*/
	public function getAllTag($order = 'id_tag DESC', $limit, $sep = ', ', $link = true, $opentag = '', $closetag = '', $class = '')
    {
		$tagsep = '';
		$tags = $this->core->podb->from('tag')->orderBy($order)->limit($limit)->fetchAll();
		foreach($tags as $tag){
			if ($link) {
				$tagsep .= $opentag.'<a class="'.$class.'" href="'.WEB_URL.'tag/'.$tag['tag_seo'].'">'.ucfirst($tag['title']).'</a>'.$closetag.$sep;
			} else {
				$tagsep .= $opentag.$tag['title'].$closetag.$sep;
			}
		}
        return rtrim($tagsep, $sep);
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar tag berdasarkan post tag.
	 *
	 * This function use to get list of post base on tag post.
	 *
	 * $tagseo = array of tag post
	 * $sep = string separator
	 * $link = boolean
	*/
	public function getPostTag($tagseo, $sep = ', ', $link = true)
    {
		$tag = '';
		$tagseoexp  = explode(',' ,$tagseo);
		foreach($tagseoexp as $exp){
			$tags = $this->core->podb->from('tag')
				->where('tag_seo', $exp)
				->limit(1)
				->fetch();
			if ($link) {
				$tag .= '<a href="'.WEB_URL.'tag/'.$tags['tag_seo'].'">'.ucfirst($tags['title']).'</a>'.$sep;
			} else {
				$tag .= $tags['title'].$sep;
			}
		}
        return rtrim($tag, $sep);
    }

	/**
	 * Fungsi ini digunakan untuk membuat daftar kategori bercabang berdasarkan parent.
	 *
	 * This function use to create list of category tree base on parent.
	 *
	 * $id_category = integer
	*/
	public function getCategoryParentTree($id_category)
    {
		$ptree = array();
		$ptree[] = "".$id_category."";
		$ctree = $this->getCategoryTree($id_category);
		$ptree = array_merge($ptree, $ctree);
		return $ptree;
	}

	/**
	 * Fungsi ini digunakan untuk membuat daftar kategori bercabang.
	 *
	 * This function use to create list of category tree.
	 *
	 * $id_category = integer
	*/
	public function getCategoryTree($id_category)
    {
		$tree = array();
		$catfuns = $this->core->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_parent', $id_category)
			->where('category_description.id_language', '1')
			->orderBy('category.id_category ASC')
			->fetchAll();
		$catfunnum = $this->core->podb->from('category')->where('id_parent', $id_category)->orderBy('id_category ASC')->count();
		if ($catfunnum > 0) {
			foreach ($catfuns as $catfun) {
				$child = $this->getCategoryParentTree($catfun['id_category']);
				if ($child) {
					$tree[] = $catfun['id_category'];
					$tree = $child;
				} else {
					$tree[] = $catfun['id_category'];
				}
			}
		}
        return $tree;
    }

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berdasarkan kategori.
	 *
	 * This function use to get list of post base on category.
	 *
	 * $limit = integer
	 * $order = string
	 * $orderall = string
	 * $category = array of category
	 * $page = integer from get active page
	 * $lang = WEB_LANG_ID
	*/
	public function getPostFromCategory($limit, $order, $orderall, $category, $page, $lang)
    {
		$offset = $this->core->popaging->searchPosition($limit, $page);
		if ($category['seotitle'] == 'all') {
			$post = $this->core->podb->from('post')
				->select(array('post_description.title', 'post_description.content'))
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post_description.id_language', $lang)
				->where('post.active', 'Y')
				->where('post.publishdate < ?', date('Y-m-d H:i:s'))
				->orderBy($orderall)
				->limit($offset.','.$limit)
				->fetchAll();
		} else {
			$post = array();
			$categorys = $this->core->podb->from('post_category')
				->where('id_category', $this->getCategoryParentTree($category['id_category']))
				->orderBy($order)
				->limit($offset.','.$limit)
				->fetchAll();
			foreach($categorys as $cat){
				$if_post = $this->getPostById($cat['id_post'], $lang);
				if (!empty($if_post['active'])) {
					$post[] = $this->getPostById($cat['id_post'], $lang);
				}
			}
		}
		return $this->arrayOrderBy($post, 'id_post', SORT_DESC);
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman kategori
	 *
	 * This function use to create pagination in category page.
	 *
	 * $limit = integer
	 * $category = array of category
	 * $page = integer from get active page
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getCategoryPaging($limit, $category, $page, $type, $prev, $next)
    {
		if ($category['seotitle'] == 'all') {
			$totaldata = $this->core->podb->from('post')->where('active', 'Y')->where('publishdate < ?', date('Y-m-d H:i:s'))->count();
			$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
			$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'category/'.$category['seotitle'], 'page', $type, $prev, $next);
		} else {
			$totaldata = $this->core->podb->from('post_category')->where('id_category', $this->getCategoryParentTree($category['id_category']))->count();
			$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
			$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'category/'.$category['seotitle'], 'page', $type, $prev, $next);
		}
		return $pagination;
	}

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berdasarkan tag.
	 *
	 * This function use to get list of post base on tag.
	 *
	 * $limit = integer
	 * $order = string
	 * $tag = array of tag
	 * $page = integer from get active page
	 * $lang = WEB_LANG_ID
	*/
	public function getPostFromTag($limit, $order, $tag, $page, $lang)
    {
		$offset = $this->core->popaging->searchPosition($limit, $page);
		$post = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('post.tag LIKE ?', '%'.$tag.'%')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy($order)
			->limit($offset.','.$limit)
			->fetchAll();
		return $post;
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman tag
	 *
	 * This function use to create pagination in tag page.
	 *
	 * $limit = integer
	 * $tag = array of tag
	 * $page = integer from get active page
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getTagPaging($limit, $tag, $page, $type, $prev, $next)
    {
		$totaldata = $this->core->podb->from('post')
			->where('post.tag LIKE ?', '%'.$tag.'%')
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->count();
		$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
		$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'tag/'.$tag, 'page', $type, $prev, $next);
		return $pagination;
	}

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post berdasarkan pencarian.
	 *
	 * This function use to get list of post base on search.
	 *
	 * $limit = integer
	 * $order = string
	 * $query = string from search
	 * $page = integer from get active page
	 * $lang = WEB_LANG_ID
	*/
	public function getPostFromSearch($limit, $order, $query, $page, $lang)
    {
		$conditions = [
			'post_description.title LIKE "%'.str_replace('-', ' ', $query).'%"',
			'post_description.content LIKE "%'.str_replace('-', ' ', $query).'%"',
			'post.tag LIKE "%'.str_replace('-', ' ', $query).'%"',
		];
		$orWhere = implode(" OR ", $conditions);
		$offset = $this->core->popaging->searchPosition($limit, $page);
		$post = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('('.$orWhere.')')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->orderBy($order)
			->limit($offset.','.$limit)
			->fetchAll();
		return $post;
    }

	/**
	 * Fungsi ini digunakan untuk membuat nomor halaman pada halaman pencarian
	 *
	 * This function use to create pagination in search page.
	 *
	 * $limit = integer
	 * $query = string from search
	 * $page = integer from get active page
	 * $lang = WEB_LANG_ID
	 * $type = 0 or 1
	 * $prev = string previous text
	 * $next = string next text
	*/
	public function getSearchPaging($limit, $query, $page, $lang, $type, $prev, $next)
    {
		$conditions = [
			'post_description.title LIKE "%'.str_replace('-', ' ', $query).'%"',
			'post_description.content LIKE "%'.str_replace('-', ' ', $query).'%"',
			'post.tag LIKE "%'.str_replace('-', ' ', $query).'%"',
		];
		$orWhere = implode(" OR ", $conditions);
		$totaldata = $this->core->podb->from('post')
			->select(array('post_description.title', 'post_description.content'))
			->leftJoin('post_description ON post_description.id_post = post.id_post')
			->where('('.$orWhere.')')
			->where('post_description.id_language', $lang)
			->where('post.active', 'Y')
			->where('post.publishdate < ?', date('Y-m-d H:i:s'))
			->count();
		$totalpage = $this->core->popaging->totalPage($totaldata, $limit);
		$pagination = $this->core->popaging->navPage($page, $totalpage, BASE_URL, 'search/'.$query, 'page', $type, $prev, $next);
		return $pagination;
	}

	/**
	 * Fungsi ini digunakan untuk mengambil daftar post gambar galeri.
	 *
	 * This function use to get list of post image gallery.
	 *
	 * $id_post = integer
	 * $order = string ASC or DESC
	*/
	public function getPostGallery($id_post, $order)
    {
		$post_gallery = $this->core->podb->from('post_gallery')
			->where('id_post', $id_post)
			->orderBy('id_post_gallery '.$order.'')
			->fetchAll();
        return $post_gallery;
    }

	/**
	 * Fungsi ini digunakan untuk menggenerate checkbox kategori.
	 *
	 * This function use for generate category checkbox
	 *
	*/
	public function generate_checkbox($id, $type, $id_post = null)
	{
		if ($type == 'add') {
			return $this->generate_child($id, "0");
		} else {
			return $this->generate_child_update($id, $id_post, "0");
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate child checkbox kategori.
	 *
	 * This function use for generate category child checkbox.
	 *
	*/
	public function generate_child($id, $exp)
	{
		$i = 1;
		$html = "";
		$indent = str_repeat("\t\t", $i);
		$catfuns = $this->core->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_parent', $id)
			->where('category_description.id_language', '1')
			->orderBy('category.id_category ASC')
			->fetchAll();
		$catfunnum = $this->core->podb->from('category')->where('id_parent', $id)->orderBy('id_category ASC')->count();
		if ($catfunnum > 0) {
			$html .= "\n\t".$indent."";
			$html .= "<ul class=\"list-unstyled\">";
			$i++;
			foreach ($catfuns as $catfun) {
				$explus = $exp + 20;
				$child = $this->generate_child($catfun['id_category'], $explus."px");
				$html .= "\n\t".$indent."";
				if ($child) {
					$i--;
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' /> ";
					$html .= $catfun['title'];
					$html .= $child;
					$html .= "\n\t".$indent."";
				} else {
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' /> ";
					$html .= $catfun['title'];
				}
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate update child checkbox kategori.
	 *
	 * This function use for generate category child update checkbox.
	 *
	*/
	public function generate_child_update($id, $id_post, $exp)
	{
		$i = 1;
		$html = "";
		$postcat = array();
		$indent = str_repeat("\t\t", $i);
		$catfuns = $this->core->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_parent', $id)
			->where('category_description.id_language', '1')
			->orderBy('category.id_category ASC')
			->fetchAll();
		$post_cats = $this->core->podb->from('post_category')
			->where('id_post', $id_post)
			->fetchAll();
		foreach($post_cats as $post_cat){
			$postcat[] = $post_cat['id_category'];
		}
		$catfunnum = $this->core->podb->from('category')->where('id_parent', $id)->orderBy('id_category ASC')->count();
		if ($catfunnum > 0) {
			$html .= "\n\t".$indent."";
			$html .= "<ul class=\"list-unstyled\">";
			$i++;
			foreach ($catfuns as $catfun) {
				if (in_array($catfun['id_category'], $postcat)) {
					$checked = 'checked';
				} else {
					$checked = '';
				}
				$explus = $exp + 20;
				$child = $this->generate_child_update($catfun['id_category'], $id_post, $explus."px");
				$html .= "\n\t".$indent."";
				if ($child) {
					$i--;
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' ".$checked." /> ";
					$html .= $catfun['title'];
					$html .= $child;
					$html .= "\n\t".$indent."";
				} else {
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' ".$checked." /> ";
					$html .= $catfun['title'];
				}
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengurutkan array.
	 *
	 * This function use to array order.
	 *
	*/
	public function arrayOrderBy()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}

}