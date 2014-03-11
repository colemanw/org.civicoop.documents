<?php

/* 
 * This class holds all information for a document
 * 
 */

class CRM_Documents_Entity_Document {
  
  protected $id;
  
  /**
   *
   * @var array 
   */
  protected $contactIds = array();
  
  /**
   *
   * @var DateTime 
   */
  protected $dateAdded;
  
  /**
   *
   * @var int ContactId of the contact who added this document 
   */
  protected $addedBy;
  
  /**
   *
   * @var DateTime 
   */
  protected $dateUpdated;
  
  /**
   *
   * @var int ContactId of the contact who updated this document 
   */
  protected $updatedBy;
  
  /**
   *
   * @var String 
   */
  protected $subject;
  
  /**
   *
   * @var CRM_Documents_Entity_DocumentFile 
   */
  protected $attachment;
  
  public function __construct() {
    $this->setDefaults();
  }
  
  /**
   * Set default values for object
   */
  protected function setDefaults() {
    $session = CRM_Core_Session::singleton();
    unset($this->id);
    $this->contactIds = array();
    $this->dateAdded = new DateTime();
    $this->addedBy = $session->get('userID');
    unset($this->dateUpdated);
    unset($this->updatedBy);
    $this->subject = '';
    $this->attachment = new CRM_Documents_Entity_DocumentFile();
  }
  
  public function getId() {
    if (!empty($this->id)) {
      return $this->id;
    } else {
      return NULL;
    }
  }
  
  public function setId($id) {
    $this->id = (int) $id;
  }
  
  public function setContactIds($contact_ids) {
    if (is_array($contact_ids)) {
      $this->contactIds = $contact_ids;
    } else {
      $this->contactIds = explode(",".$contact_ids);
    }
  }
  
  public function addContactId($contact_id) {
    if (!in_array($contact_id, $this->contactIds)) {
      $this->contactIds[] = $contact_id;
    }
  }
  
  public function getContactIds() {
    return $this->contactIds;
  }
  
  public function setAttachment(CRM_Documents_Entity_DocumentFile $file) {
    $this->attachment = $file;
  }
  
  public function getAttachment() {
    return $this->attachment;
  }
  
  /**
   * Returns a download link (including the <a href> tag
   * 
   * @param String $title
   * @param String $classes
   * @return String
   */
  public function getAttachmentDownloadLink($title='', $classes='') {
    $t = $this->attachment->cleanname;
    if (strlen($title)) {
      $t = $title;
    }
    var_dump('<a href="'.$this->attachment->url.'" title="'.$this->attachment->cleanname.'" class="'.$classes.'">'.$t.'</a>'); exit();
    
  }
  
  
  public function setAddedBy($addedBy) {
    $this->addedBy = $addedBy;
  }
  
  public function getAddedBy() {
    return $this->addedBy;
  }
  
  public function setDateAdded(DateTime $date) {
    $this->dateAdded = $date;
  }
  
  public function getDateAdded() {
    return $this->dateAdded;
  }
  
  public function getUpdatedBy() {
    if (isset($this->updatedBy)) {
      return $this->updatedBy;
    } else {
      return NULL;
    }
  }
  
  public function setUpdatedBy($updatedBy) {
    $this->updatedBy = $updatedBy;
  }
  
  public function getDateUpdated() {
    if (isset($this->dateUpdated)) {
      return $this->dateUpdated;
    } else {
      return NULL;
    }
  }
  
  public function setDateUpdated(DateTime $date) {
    $this->dateUpdated = $date;
  }
  
  public function getSubject() {
    return $this->subject;
  }
  
  public function setSubject($subject) {
    $this->subject = $subject;
  }
  
  public function getFormattedContacts() {
    $contacts = '';
    foreach($this->contactIds as $cid) {
      if (strlen($contacts)) {
        $contacts .= ', ';
      }
      $contacts .= $this->formatContact($cid);
    }
    return $contacts;
  }
  
  public function getFormattedDateAdded() {
    return $this->formateDate($this->getDateAdded());
  }
  
  public function getFormattedDateUpdated() {
    return $this->formateDate($this->getDateUpdated());
  }
  
  public function getFormattedAddedBy($link=TRUE) {
    return $this->formatContact($this->getAddedBy(), $link);
  }
  
  public function getFormattedUpdatedBy($link=TRUE) {
    return $this->formatContact($this->getUpdatedBy(), $link);
  }
  
  private function formatContact($cid, $link=TRUE) {
    $return = '';
    if ($cid) {
      $display_name = CRM_Contact_BAO_Contact::displayName($cid);
      if ($link) {
        $return = '<a class="" href="' . CRM_Utils_System::url('civicrm/documents/add', 'reset=1&cid=' . $cid) . '" >'.$display_name.'</a>';
      } else {
        $return = $display_name;
      }
    }
    return $return;
  }
  
  private function formateDate($date) {
    $return = '';
    if ($date) {
      $config = CRM_Core_Config::singleton();
      $return = CRM_Utils_Date::customFormat($date->format('Y-m-d'));
    }
    return $return;
  }
}
