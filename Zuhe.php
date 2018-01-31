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

            for ($i = 0; $i < count($string); $i++){
                for($j = 0; $j < count($string) ; $j++)
                    echo $array[$i][$j] .' ';
                 echo "\n";
            }

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
                    if($sets[$i] != $grid1){
                        $grid2 = $sets[$i];
//                        grid2 and grid all are across
                        if($grid2->flag == 1 && $grid->flag == 1){

                            if($grid2->row_col[0][0] != $grid->row_col[0][0])
                                continue;
                            else{
                                if($grid2->row_col[0][1] > $grid->row_col[strlen($grid->str)-1][1] || $grid2->row_col[strlen($grid2->str)-1][1] < $grid->row_col[0][1])
                                    continue;
                                else
                                    return false;
                            }

                        }else if($grid2->flag == 1 && $grid->flag == 2){

                            if($grid->row_col[0][1] > $grid2->row_col[ strlen($grid2->str) -1][1] || $grid->row_col[0][1] < $grid2->row_col[0][1])
                                continue;
                            else{
                                if($grid2->row_col[0][0] <= $grid[strlen($grid2->str)-1][0] && $grid2->row_col[0][0] >= $grid->row_col[0][0]){
                                    $col = $grid->row_col[0][1];
                                    $row = $grid2->row_col[0][0];
                                    $grid_index = 0;
                                    $grid2_index = 0;
                                    for($j = 0; $j < strlen($grid->str) ; $j++)
                                        if($grid->row_col[$j][0] == $row && $grid->row_col[$j][1] == $col){
                                            $grid_index = $j;
                                            break;
                                        }
                                    for($j = 0; $j < strlen($grid2->str) ; $j++)
                                        if($grid2->row_col[$j][0] == $row && $grid2->row_col[$j][1] == $col){
                                            $grid2_index = $j;
                                            break;
                                        }
                                        if($grid->str[$grid_index] == $grid2->str[$grid2_index])
                                            continue;
                                        else
                                            return false;
                                }else
                                    continue;
                            }

                        }else if($grid2->flag == 2 && $grid->flag == 1){
                            $t = $grid;
                            $grid = $grid2;
                            $grid2 = $t;
                            if($grid->row_col[0][1] > $grid2->row_col[ strlen($grid2->str) -1][1] || $grid->row_col[0][1] < $grid2->row_col[0][1])
                            {
                                $t = $grid;
                                $grid = $grid2;
                                $grid2 = $t;
                                continue;
                            }
                            else{
                                if($grid2->row_col[0][0] <= $grid[strlen($grid2->str)-1][0] && $grid2->row_col[0][0] >= $grid->row_col[0][0]){
                                    $col = $grid->row_col[0][1];
                                    $row = $grid2->row_col[0][0];
                                    $grid_index = 0;
                                    $grid2_index = 0;
                                    for($j = 0; $j < strlen($grid->str) ; $j++)
                                        if($grid->row_col[$j][0] == $row && $grid->row_col[$j][1] == $col){
                                            $grid_index = $j;
                                            break;
                                        }
                                    for($j = 0; $j < strlen($grid2->str) ; $j++)
                                        if($grid2->row_col[$j][0] == $row && $grid2->row_col[$j][1] == $col){
                                            $grid2_index = $j;
                                            break;
                                        }
                                    if($grid->str[$grid_index] == $grid2->str[$grid2_index]){
                                        $t = $grid;
                                        $grid = $grid2;
                                        $grid2 = $t;
                                        continue;
                                    }

                                    else
                                        return false;
                                }else{
                                    $t = $grid;
                                    $grid = $grid2;
                                    $grid2 = $t;
                                    continue;
                                }
                            }
                        }else{
                            if($grid->row_col[0][1] != $grid2->row_col[0][1])
                                continue;
                            else{
                                if($grid2->row_col[strlen($grid2->str)-1][0] > $grid->row_col[0][0] || $grid[strlen($grid->str)-1][0] > $grid2->row_col[0][0])
                                    continue;
                                else
                                    return false;
                            }
                        }
                    }
                }

                return true;
        }

//        $set已经放置好的单词，$grid 将要放置的；
        public function judgePlace($set,$grid){

            global $array;
            $len = count($set);
            $l1 = 0;
            $l2 = 0;

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
                        for ($k = 0; $k < strlen($grid1->str) ; $k++){
                            if($temp != -1)
                                break;
                            for($j = 0; $j < strlen($grid->str) ; $j++){
                                if( $grid1->str[$k] == $grid->str[$j]){
                                    $temp = $k;
                                    $temp_grid = $j;
                                    break;
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
                        $l1 = $temp;
                        $l2 = $temp_grid;

                    }else
                        continue;

                    if($this->judgeOtherCross($grid,$grid1,$set) == false)
                        continue;
                    else{
                        $num = count($grid1->set);
                        $grid1->set[$num++]  = $l1;
                        $num = count($grid->set);
                        $grid->set[$num++] = $temp_grid;
                        $set[$len++] = $grid;
                        return $grid;

                    }
//
                }//if 两个单词有交点

           }// for和每个单词比较

            return null;
        }


//      开始放置单词；
        public function placeGrid( $grid ){
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
                    if($grid[$i]->row_col[$j][1] <$col_max)
                        $col_max = $grid[$i]->row_col[$j][1];
                }
            }

            $result1 = array();
            $result = array();
            for ($i = 0; $i <= $row_max-$row_min; $i++){
                array_push($result1,array());
                array_push($result,array());
            }

            for ($i = 0; $i <= $row_max-$row_min ;$i++)
                for ($j = 0; $j <= $col_max-$col_min; $j++)
                    $result1[$i][$j] = ' ';

            for($i = 0; $i< count($grid); $i++){
                for($j = 0; $j <strlen($grid[$i]->str) ; $j++){
                    $row = $grid[$i]->row_col[$j][0] - $row_min;
                    $col = $grid[$i]->row_col[$j][1] - $col_min;
                    $result1[$row][$col] = $grid[$i]->str[$j];
 echo $grid[$i]->row_col[$j][0] . " " .$grid[$i]->row_col[$j][1] . "\n";
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
            $string = array("coach","woman","pencil","sister","month","cat","hour","attribute","new","string","judge","show","choose","not");
//            $string = array("random","table","coach");
//  初始化array二维数组；
            $this->judge();
            $array_a = array();
            for($i = 0; $i< count($string) ; $i++)
                $array_a[$i] = $i;
            $result= array();
            $this->recursion($array_a,0,$result,0,3);


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
