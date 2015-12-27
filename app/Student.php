<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

	protected $table = 'xs_xsb';

	protected $primaryKey = 'c_xh';

	public $incrementing = false;

	public $timestamps = false;
}
