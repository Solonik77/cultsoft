<?php
/**
 * Blog model
 */
class Blog extends App_Model_Abstract
{
    protected $_table = 'Blog_DbTable_Blog';

    public function __construct ()
    {
        parent::__construct();
    }
    public function loadBlogs ($sortByField = 'id', $sortOrder = 'asc')
    {
        $sortOrder = strtoupper((($sortOrder === 'asc') ? $sortOrder : 'desc'));
        $cols = array();
        $cols['blog'] = array('id' , 'type' , 'date_created');
        $cols['i18n_blog'] = array('title');
        $fields = array_merge($cols['blog'], $cols['i18n_blog']);
        if (! in_array($sortByField, $fields)) {
            $sortByField = (string) $fields[0];
        }
        $select = $this->_table->select()->setIntegrityCheck(false);
        $select->from(DB_TABLE_PREFIX . 'blog', $cols['blog']);
        $select->join(DB_TABLE_PREFIX . 'i18n_blog', DB_TABLE_PREFIX . 'blog.id = ' . DB_TABLE_PREFIX . 'i18n_blog.blog_id', $cols['i18n_blog']);
        $select->join(DB_TABLE_PREFIX . 'members', DB_TABLE_PREFIX . 'members.id = ' . DB_TABLE_PREFIX . 'blog.member_id', array('first_name' , 'last_name'));
        $select->where(DB_TABLE_PREFIX . 'i18n_blog.lang_id = ?', App::front()->getParam('requestLangId'))->order($sortByField . ' ' . $sortOrder);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(App::config()->items_per_page);
        $paginator->setCurrentPageNumber(App::front()->getRequest()->getParam('page', 1));
        return $paginator;
    }
    public function getBlogTypes ()
    {
        return array('private' => 'Personal blog' , 'collaborative' => 'Collaborative blog (community)');
    }
    /**
     * @param $type the $type to set
     */
    public function setType ($type)
    {
        $this->_attributes['type'] = $this->type = $type;
    }
    /**
     * @param $member_id the $member_id to set
     */
    public function setMemberId ($member_id)
    {
        $this->_attributes['member_id'] = $this->member_id = $member_id;
    }
    /**
     * @param $date_updated the $date_updated to set
     */
    public function setDateUpdated ($date_updated)
    {
        $this->_attributes['date_updated'] = $this->date_updated = $date_updated;
    }
    /**
     * @param $date_created the $date_created to set
     */
    public function setDateCreated ($date_created)
    {
        $this->_attributes['date_created'] = $this->date_created = $date_created;
    }
    /**
     * @param $fancy_url the $fancy_url to set
     */
    public function setFancyUrl ($fancy_url)
    {
        $this->_attributes['fancy_url'] = $this->fancy_url = $fancy_url;
    }
    /**
     * @param $id the $id to set
     */
    public function setId ($id)
    {
        $this->_attributes['id'] = $this->id = $id;
    }
    /**
     * @param $_table the $_table to set
     */
    public function setTable ($_table)
    {
        $this->_table = $_table;
    }
    /**
     * @return the $type
     */
    public function getType ()
    {
        return $this->type;
    }
    /**
     * @return the $member_id
     */
    public function getMemberId ()
    {
        return $this->member_id;
    }
    /**
     * @return the $date_updated
     */
    public function getDateUpdated ()
    {
        return $this->date_updated;
    }
    /**
     * @return the $date_created
     */
    public function getDateCreated ()
    {
        return $this->date_created;
    }
    /**
     * @return the $fancy_url
     */
    public function getFancyUrl ()
    {
        return $this->fancy_url;
    }
    /**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * @return the $_table
     */
    public function getTable ()
    {
        return $this->_table;
    }
}
