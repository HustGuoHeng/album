<?php
namespace App\Http\Libraries\Page;

/**
 * 处理获取到的页数、每页显示的新闻数目、以及总的数据，使其保存在一个合理的数值
 * Class PageService
 * @package App\Tools\Page
 */
class Page
{
    /*
     * @var int 当前页数
     */
    protected $page;

    /**
     * @var int 每页最大输出数据条数
     */
    protected $number;

    /**
     * @var int 数据总量
     */
    protected $count;

    /**
     * @var int 最小页数
     */
    protected $minPage = 1;

    public function __construct($page, $number, $count)
    {
        $this->page   = intval($page);
        $this->number = intval($number);
        $this->count  = intval($count);

        $this->handleNumber();
        $this->handlePage();
    }

    public function getMaxPage()
    {
        return ceil($this->count / $this->number);
    }

    public function handleNumber()
    {
        $this->number = $this->number > 0 ? $this->number : 1;
    }

    public function handlePage()
    {
        $maxPage = $this->getMaxPage();

        $this->page = $this->page > $maxPage ? $maxPage : $this->page;
        $this->page = $this->page < $this->minPage ? $this->minPage : $this->page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getSafeNumber()
    {
        return $this->number > $this->count ? $this->count : $this->number;
    }

    public function getMinPage()
    {
        return $this->minPage;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getOffset()
    {
        return ($this->page - 1) * $this->number;
    }


}