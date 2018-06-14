<?php

class PlaylistType {
    const TOP = 1;
}

class PlaylistM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $type                = null;
    public $rank                = null;
    public $company_idx         = null;
    public $date                = null;
    public $svm_processed       = null;
    public $hmm_processed       = null;
    public $created_date_time   = null;
    public $status              = 1;

    public $company_name        = null;
    public $price               = null;
    public $prev_diff           = null;
    public $percentage          = null;
    public $open                = null;
    public $high                = null;
    public $low                 = null;
    public $volume              = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new PlaylistM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setType($type) { $this->type = $type; return $this; }
    public function getType() { return $this->type; }

    public function setRank($rank) { $this->rank = $rank; return $this; }
    public function getRank() { return $this->rank; }

    public function setCompanyIdx($company_idx) { $this->company_idx = $company_idx; return $this; }
    public function getCompanyIdx() { return $this->company_idx; }

    public function setDate($date) { $this->date = $date; return $this; }
    public function getDate() { return $this->date; }

    public function setSvmProcessed($svm_processed) { $this->svm_processed = $svm_processed; return $this; }
    public function getSvmProcessed() { return $this->svm_processed; }

    public function setHmmProcessed($hmm_processed) { $this->hmm_processed = $hmm_processed; return $this; }
    public function getHmmProcessed() { return $this->hmm_processed; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    /* ---------------------- */

    public function setCompanyName($status) { $this->company_name = $company_name; return $this; }
    public function getCompanyName() { return $this->company_name; }

    public function setPrice($price) { $this->price = $price; return $this; }
    public function getPrice() { return $this->price; }

    public function setPrevDiff($prev_diff) { $this->prev_diff = $prev_diff; return $this; }
    public function getPrevDiff() { return $this->prev_diff; }

    public function setPercentage($percentage) { $this->percentage = $percentage; return $this; }
    public function getPercentage() { return $this->percentage; }

    public function setOpen($open) { $this->open = $open; return $this; }
    public function getOpen() { return $this->open; }

    public function setHigh($high) { $this->high = $high; return $this; }
    public function getHigh() { return $this->high; }

    public function setLow($low) { $this->low = $low; return $this; }
    public function getLow() { return $this->low; }

    public function setVolume($volume) { $this->volume = $volume; return $this; }
    public function getVolume() { return $this->volume; }

    //// ------------------------------ action function

    public function getList( $sortBy = '`p`.`rank`', $sortDirection = 'asc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"`p`.`idx`,`p`.`rank`,`p`.`date`, `p`.`company_idx`,`c`.`name` as company_name,`cs`.`price`,`cs`.`prev_diff`,`cs`.`percentage`,`cs`.`open`,`cs`.`high`,`cs`.`low`,`cs`.`volume` ";
		$query .= "FROM ";
        $query .=   "`playlist` as `p`, ";
        $query .=   "`company` as `c`, ";
        $query .=   "`company_stock` as `cs` ";
		$query .= "WHERE ";
        $query .=  "`p`.`company_idx`=`c`.`idx` AND ";
        $query .=  "`p`.`company_stock_idx`=`cs`.`idx` AND ";
        if ($this->date!=null) { $query .= "`p`.`date`=? AND "; }
        if ($this->company_idx!=null) { $query .= "`p`.`company_idx`=? AND "; }
        if ($this->svm_processed!=null) { $query .= "`p`.`svm_processed`=? AND "; }
        if ($this->hmm_processed!=null) { $query .= "`p`.`hmm_processed`=? AND "; }
		$query .=	"`p`.`status`=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";
        if (!$total_count) { $query .= (($limit=='-1')&&($offset=='-1'))?'':"limit ? offset ? "; }

		$fmt = "";
        if ($this->date!=null) { $fmt .= "s"; }
        if ($this->company_idx!=null) { $fmt .= "i"; }
        if ($this->svm_processed!=null) { $fmt .= "i"; }
        if ($this->hmm_processed!=null) { $fmt .= "i"; }
        $fmt .= "i";
        if (!$total_count) { $fmt .= (($limit=='-1')&&($offset=='-1'))?'':"ii";  }

		$params = array($fmt);
        if ($this->date!=null) { $params[] = &$this->date; }
        if ($this->company_idx!=null) { $params[] = &$this->company_idx; }
        if ($this->svm_processed!=null) { $params[] = &$this->svm_processed; }
        if ($this->hmm_processed!=null) { $params[] = &$this->hmm_processed; }
		$params[] = &$this->status;

		if ( $total_count ) {
            return $this->postman->returnDataObject( $query, $params );
        } else {
            if (($limit!='-1')&&($offset!='-1')) {
                $params[] = &$limit;
                $params[] = &$offset;
            }
            return array_map(function($item) {
                return PlaylistM::new($item);
            }, $this->postman->returnDataList( $query, $params ));
		}
    }
}