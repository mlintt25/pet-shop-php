<?php
class BlogModel extends Model {
    public function tableFill()
    {
        return '';
    }

    public function fieldFill()
    {
        return '';
    }

    public function primaryKey()
    {
        return '';
    }

    // Lấy danh sách bài viết theo danh mục
    // SELECT blog.*, blog_categories.name 
    // FROM blog INNER 
    //JOIN blog_categories ON blog.blog_category_id = blog_categories.id;
    public function handleGetListBlog() {
        $listBlog = $this->db->table('blog')
            ->select('blog.title, blog.slug, blog.content, blog.view_count, 
                blog.comment_count, blog.thumbnail, blog.descr, blog.create_at, 
                blog_categories.name')
            ->join('blog_categories', 'blog.blog_category_id = blog_categories.id')
            ->get();

        $response = [];
        $organizedResult = [];
        $checkNull = false;

        if (!empty($listBlog)):
            foreach ($listBlog as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            foreach ($listBlog as $key => $item):
                $blogCategory = $item['name'];
                unset($item['name']);

                $organizedResult[$blogCategory][] = $item;
            endforeach;
        endif;

        $response = $organizedResult;

        return $response;
    }

 
}