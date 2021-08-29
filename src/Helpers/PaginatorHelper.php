<?php
namespace App\Helpers;

class PaginatorHelper
{
    private $pageNo;
    private $perPage;
    private $offset;
    private const PER_PAGE = 10;

    /**
     * PaginatorHelper constructor.
     * @param $input
     */
    public function __construct($input){
        $this->pageNo = $input['page'] ?? 1;
        $this->perPage = $input['per_page'] ?? self::PER_PAGE;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return ($this->pageNo - 1) * $this->perPage;
    }

    /**
     * @return int|mixed
     */
    public function getPageNo(): int
    {
        return $this->pageNo;
    }

    /**
     * @return int|mixed
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }



}