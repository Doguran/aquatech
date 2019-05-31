<?php
class FileModel{
    public $cat_id, $title = "SUPERMOIKA", $keywords, $seo_desc;
	public function render($file) {

		ob_start();
		include($file);
		return ob_get_clean();
	}
}