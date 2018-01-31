<?php


    Class Grid{
        //	1：across；2，down；0:还没有放置；
        public $flag;
        //	单词
        public $str;
        //	单词在array数组中的顺序；
        public $index;
        //	相对第一个单词的位置；二维数组
        public  $row_col;
        //	该单词已经和其他单词相交的字符索引；set集合
        public $set;

    }


    Class Zuhe {
      public $string;
        public $array;
        public $map;
        public $flag;

        public function judgecross($str1,$str2){
            $t = array();
            for( $i = 0; $i < strlen($str1) ; $i++){
                for($j = 0; $j < strlen($str2) ; $j++){

                    if($str1[$i] == $str2[$j]){
                        $t[0] = $i;
                        $t[1] = $j;
                        return $t;
                    }
                }
            }
            $t[0] = -1;
            $t[1] = -1;
            return $t;
        }

//        初始化array数组，判断字符串两两相交情况；
        public function judge(){
            global $string;
            global $array;
            $array = array();

            for($i = 0; $i <count($string) ; $i++)
                array_push($array,array());

            for ($i = 0; $i < count($string); $i++)
                for ($j = 0; $j < count($string); $j++)
                    $array[$i][$j] = -1;


            for ($i = 0; $i < count($string); $i++){
                for($j = $i+1; $j< count($string); $j++){
                    $array_cross = $this->judgecross($string[$i],$string[$j]);
                    $array[$i][$j] = $array_cross[0];
                    $array[$j][$i] = $array_cross[1];
                }
            }

//            for ($i = 0; $i < count($string); $i++){
//                for($j = 0; $j < count($string) ; $j++)
//                    echo $array[$i][$j] .' ';
//                 echo "\n";
//            }

            return $array;
        }

//初始化 Grid数组

        public function initGrid($array_a){
            global $string;
            $len = count($array_a);
            $grid = array();
            for($i = 0; $i< $len; $i++){
                $temp = new Grid();
                $temp->str =  $string[$array_a[$i]];
                $temp->index = $array_a[$i];
                $two = array();
                for($j = 0; $j < strlen($temp->str) ; $j++)
                    array_push($two,array());
                $temp->row_col = $two;
                $temp->set = array();
                array_push($grid,$temp);
            }

            return $grid;
        }

        public  function judgen($num){
            global $array;
            $setgood = array();
            $k = 0;
            for($i = 0; $i < count($num) ; $i++)
                for ($j = 0; $j < count($num) ; $j++)
                    if($array[$num[$i]][$num[$j]] != -1){
                        if(in_array($num[$i],$setgood) == false)
                            $setgood[$k++] = $i;
                        if(in_array($num[$j],$setgood) == false)
                            $setgood[$k++] = $j;
                    }

            if(count($setgood) == count($num))
                return true;
            else
                return false;
        }

        public function judgeOtherCross($grid,$grid1,$sets){
            $len = count($sets);
                for ($i = 0; $i < $len; $i++){
//                    except grid1 others think
//                    if($sets[$i] != $grid1){
                        $grid2 = $sets[$i];
echo " judgeOtherCross " .$grid2->str ." " . $grid->str ."\n";

                        for ($j = 0; $j < count($grid2->str); $j++){
                            for ($k = 0; $k < count($grid->str) ; $k++){
                                if($grid2->row_col[$j][0] == $grid->row_col[$k][0] && $grid2->row_col[$j][1] == $grid->row_col[$k][1]){
echo " judgeOtherCross " . $grid2->str[$j]  ." " .  $grid->str[$k] . "\n";
                                    if($grid2->str[$j] == $grid->str[$k])
                                        continue;
                                    else
                                        return false;
                                }
                            }
                        }
//                    }
                }

                return true;
        }

//        $set已经放置好的单词，$grid 将要放置的；
        public function judgePlace(&$set,&$grid){

            global $array;
            $len = count($set);

            for($i = 0; $i < $len ; $i++){
                $grid1 = $set[$i];
//                说明有相交点；
                if($array[$grid1->index][$grid->index] != -1){
//				判断相交点是否已经被占用，如果被占用，重新找新交点；
//				temp == -1 说明没有交点，其他有交点且交点就是temp (grid1);temp_grid(grid)
                     $temp = -1;
                     $temp_grid = -1;
                    if(in_array($array[$grid1->index][$grid->index],$grid1->set) == false){

                        $temp = $array[$grid1->index][$grid->index];
                        $temp_grid = $array[$grid->index][$grid1->index];
                    }else{
//                        找新的相交点
echo "set: " .   $grid1->set[0] . " index1 " . $grid1->index ." index2 " . $grid->index ."\n";
                        for ($k = 0; $k < strlen($grid1->str) ; $k++){
                            if($temp != -1)
                                break;
                            for($j = 0; $j < strlen($grid->str) ; $j++){
                                if( $grid1->str[$k] == $grid->str[$j] ){
// echo "k= " .  $grid1->str[$k] ." j= " . $grid->str[$j] ."\n";
                                    if(in_array($k,$grid1->set) == false){
  echo "k= " .  $grid1->str[$k] ." j= " . $grid->str[$j] ."\n";
                                        $temp = $k;
                                        $temp_grid = $j;
                                    }
                                }
                            }
                        }
                    }
//                    两个单词相交,修改grid的row_col坐标；
                    if($temp != -1){
//                        grid1是 across，grid就是down
                        if($grid1->flag == 1){
                            $grid->flag = 2;
//                            col same
                            for ($i = 0; $i < strlen($grid->str) ; $i++)
                                $grid->row_col[$i][1] = $grid1->row_col[$temp][1];
                            $m = $grid1->row_col[$temp][0];
                            for ($i = $temp_grid; $i>= 0; $i--)
                                $grid->row_col[$i][0] = $m++;
                            $m = $grid1->row_col[$temp][0];
                            for($i = $temp_grid; $i <  strlen($grid->str) ; $i++)
                                $grid->row_col[$i][0] = $m--;
                        }else{
//                        grid1是down，grid就是across；
                            $grid->flag = 1;
                            for($i = 0; $i < strlen($grid->str) ; $i++)
                                $grid->row_col[$i][0] = $grid1->row_col[$temp][0];
                            $m = $grid1->row_col[$temp][1];
                            for($i = $temp_grid; $i >= 0; $i--)
                                $grid->row_col[$i][1] = $m--;
                            $m = $grid1->row_col[$temp][1];
                            for($i = $temp_grid; $i < strlen($grid->str); $i++)
                                $grid->row_col[$i][1] = $m++;
                        }

                    }else
                        continue;

                    if($this->judgeOtherCross($grid,$grid1,$set) == false)
                        continue;
                    else{
                        $num = count($grid1->set);
                        $grid1->set[$num++]  = $temp;
                        $num = count($grid->set);
                        $grid->set[$num++] = $temp_grid;
                        $set[$len++] = $grid;
 echo "\nstr1 " . $grid1->str .  " str2: " .$grid->str ." temp " .$temp . " temp-grid " . $temp_grid .  "\n";
                        return $grid;

                    }
//
                }//if 两个单词有交点

           }// for和每个单词比较

            return null;
        }


//      开始放置单词；
        public function placeGrid( &$grid ){
//            分成两部分，已经放置的单词，未放置的单词
            $set = array();
            $set[0] = $grid[0];
//           初始化第一个单词
            $grid[0]->flag = 1;
            for($i = 0; $i < strlen($grid[0]->str) ; $i++){
                $grid[0]->row_col[$i][0] = 0;
                $grid[0]->row_col[$i][1] = $i;

            }

            //		循环 grid.length次，至少每次找到一个有用的单词，如果一次下来找不到单词，那就报错；
            for ($i = 0; $i < count($grid) ; $i++){
                $f = false;
                for($j = 0; $j < count($grid) ; $j++){
                    if(in_array($grid[$j],$set) == false){
echo "set_length:" . count($set) ."\n";
                        if($this->judgePlace($set,$grid[$j]) == null){
                            $grid[$j]->flag = 0;
                            $grid[$j]->set = array();
                            $temp = array();
                            for($k = 0; $k < count($grid[$j]->str) ; $k++)
                                array_push($temp,array());
                            $grid[$j]->row_col = $temp;

                        }else
                            $f = true;
                    }

                }//for
                if(count($set) == count($grid))
                    break;
                if($f == false)
                    return null;
            }//for

            return $grid;
        }

        public function generateGraph($grid){

             $col_min = 0;
             $row_min = 0;
             $col_max = 0;
             $row_max = 0;
            for($i = 0; $i< count($grid); $i++){
                for($j = 0; $j <strlen($grid[$i]->str) ; $j++){
                    if($grid[$i]->row_col[$j][0] <$row_min)
                        $row_min = $grid[$i]->row_col[$j][0];
                    if($grid[$i]->row_col[$j][0] >$row_max)
                        $row_max = $grid[$i]->row_col[$j][0];
                    if($grid[$i]->row_col[$j][1] <$col_min)
                        $col_min = $grid[$i]->row_col[$j][1];
                    if($grid[$i]->row_col[$j][1] > $col_max)
                        $col_max = $grid[$i]->row_col[$j][1];
                }
            }
echo " row_min " .  $row_min ." col_min " .  $col_min . " row_max " .  $row_max ." col_max " .  $col_max . "\n";
            $result1 = array();
            $result = array();
            for ($i = 0; $i <= $row_max-$row_min +1 ; $i++){
                array_push($result1,array());
                array_push($result,array());
            }

            for ($i = 0; $i <= $row_max-$row_min +1 ;$i++)
                for ($j = 0; $j <= $col_max-$col_min+1; $j++)
                    $result1[$i][$j] = ' ';

            for($i = 0; $i< count($grid); $i++){
                for($j = 0; $j <strlen($grid[$i]->str) ; $j++){
                    $row = $grid[$i]->row_col[$j][0] - $row_min;
                    $col = $grid[$i]->row_col[$j][1] - $col_min;
                    $result1[$row][$col] = $grid[$i]->str[$j];
 echo $grid[$i]->row_col[$j][0] . " " .$grid[$i]->row_col[$j][1] . "  ". $grid[$i]->str[$j] ."\n";
                }
  echo "#####\n";
            }

            for ($i = 0; $i< count($result1); $i++)
                for ($j = 0; $j < count($result1[$i]); $j++){
                    $result[count($result1)-$i-1][$j] = $result1[$i][$j];
                }

            for ($i = 0; $i< count($result); $i++){
                for ($j = 0; $j < count($result[$i]); $j++){
echo $result[$i][$j] . " ";
                }
 echo "\n";
            }


            return $result;

        }

        public function recursion($array,$k,$result,$i,$len){
            global $flag;
            global $string;
            global $map;
            if($flag == true)
                return ;
            if($i >= $len){
                $j = 0;
                for(;$j<$len-1;$j++)
                     echo $result[$j] .' ';
                echo $result[$j] . "\n";

                if($this->judgen($result) == true){
                    $grid = $this->initGrid($result);
                    if($this->placeGrid($grid) != null){

                        $map = $this->generateGraph($grid);
                        $flag = true;
                    }

                }
                return;
            }

            if($k >= count($array))
                return ;

            $result[$i] = $array[$k];
            $this->recursion($array,$k+1,$result,$i+1,$len);
            $this->recursion($array,$k+1,$result,$i,$len);
        }

        public function main(){

            global $string;
            global $map;
            $string = array("random","coach","woman","pencil","sister","month","cat","hour","attribute","new","string","judge","show","choose","not");
//            $string = array("random","table","coach");
//  初始化array二维数组；
            $this->judge();
            $array_a = array();
            for($i = 0; $i< count($string) ; $i++)
                $array_a[$i] = $i;
            $result= array();
            $this->recursion($array_a,0,$result,0,6);
//
//            for ($i = 0; $i < count($map) ; $i++){
//                for ($j = 0; $j < count($map[$i]) ; $j++)
//                    echo $map[$i][$j] . " ";
//                echo "\n";
//            }

        }
    }
    $a = new Zuhe();
//  初始化string数组；
    $a->main();
?>
