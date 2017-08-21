<?php

/**
 * 九宫格定位
 */
class GridLocation {
	
	/**
	 * 循环遍历
	 *
	 * @param integer $x        	
	 * @param integer $y        	
	 *
	 * @return integer 所在块
	 */
	public function location1($x, $y) {
		$grid_x = $grid_y = 0;
		
		foreach ( $this->x_ranges as $x_key => $x_range ) {
			if ($x > $x_range) {
				$grid_x = $x_key + 1;
			} else {
				$grid_x = $x_key;
				break;
			}
		}
		
		foreach ( $this->y_ranges as $y_key => $y_range ) {
			if ($y > $y_range) {
				$grid_y = $y_key + 1;
			} else {
				$grid_y = $y_key;
				break;
			}
		}
		
		return $this->getBlock ( $grid_x, $grid_y );
	}
	
	/**
	 * 直接用数组函数
	 *
	 * @param integer $x        	
	 * @param integer $y        	
	 *
	 * @return integer 所在块
	 */
	public function location2($x, $y) {
		$ranges_x = $this->x_ranges;
		$ranges_y = $this->y_ranges;
		
		$ranges_x [] = $x;
		$ranges_y [] = $y;
		
		sort ( $ranges_x, SORT_ASC );
		sort ( $ranges_y, SORT_DESC );
		
		$grid_x = array_search ( $x, $ranges_x );
		$grid_y = array_search ( $y, $ranges_y );
		
		return $this->getBlock ( $grid_x, $grid_y );
	}
	
	/**
	 * 确定每个块的范围
	 *
	 * @param integer $x        	
	 * @param integer $y        	
	 *
	 * @return integer 所在块
	 */
	public function location3($x, $y) {
		$grid_x = $grid_y = 0;
		
		if (empty ( $this->block_ranges )) {
			$this->getBlcokRanges ();
		}
		
		foreach ( $this->block_ranges as $range ) {
			// $range [minx, miny, maxx, maxy, gridx, gridy];
			
			if ($x > $range [0] && $x < $range [2] && $y > $range [1] && $y < $range [3]) {
				$grid_x = $range [4];
				$grid_y = $range [5];
				break;
			}
		}
		
		return $this->getBlock ( $grid_x, $grid_y );
	}
	
	/**
	 * 计算每个分块的范围
	 */
	public function getBlcokRanges() {
		$this->block_ranges = array ();
		
		$x_num = count ( $this->x_ranges );
		$y_num = count ( $this->y_ranges );
		
		for($i = 1; $i <= $x_num; $i ++) {
			for($j = 1; $j <= $y_num; $j ++) {
				$minx = $this->x_ranges [$i - 1];
				$miny = $this->y_ranges [$j - 1];
				$maxx = $i == $x_num ? self::MAX_X : $this->x_ranges [$i];
				$maxy = $j == $y_num ? self::MAX_Y : $this->y_ranges [$j];
				
				$this->block_ranges [] = [ 
						$minx,
						$miny,
						$maxx,
						$maxy,
						$i,
						$j 
				];
			}
		}
	}
	
	/**
	 * 所在区块编号
	 *
	 * @param integer $x        	
	 * @param integer $y        	
	 * @return 返回所在区块编号；如果不存在返回NULL
	 */
	public function getBlock($x, $y) {
		return $x * 3 - ($y - 1);
	}
	
	/**
	 * 设置格子间距
	 *
	 * @param array $range        	
	 */
	public function setRanges($x_ranges, $y_ranges) {
		$this->x_ranges = $x_ranges;
		$this->y_ranges = $y_ranges;
	}
	
	/**
	 * 水平方向的格子间距
	 */
	public $x_ranges = [ 
			0,
			10,
			25 
	];
	
	/**
	 * 垂直方向的格子间距
	 */
	public $y_ranges = [ 
			0,
			40,
			65 
	];
	
	/**
	 * 水平方向最大值
	 */
	const MAX_X = 65536;
	
	/**
	 * 垂直方向最大值
	 */
	const MAX_Y = 65536;
	
	/**
	 * 每个分块范围
	 */
	public $block_ranges = array ();
}

// 调用
$x = 57;
$y = 89;
$x_ranges = [ 
		0,
		10,
		25 
];
$y_ranges = [ 
		0,
		40,
		65 
];

$grid = new GridLocation ();
$grid->setRanges ( $x_ranges, $y_ranges );

// 适合数据简单，调用次数不多的情况
echo $grid->location1 ( $x, $y );

// 与第一种方法相比适合数据较为复杂的情况
echo $grid->location2 ( $x, $y );

// 第三种方法适合多次定位
echo $grid->location3 ( $x, $y );
