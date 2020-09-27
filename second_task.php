<?php

	/*

		Дан массив произвольного размера с числами в пределах от 1 до 1,000,000.
		В этом массиве все числа уникальные, кроме одного числа, которое повторяется два раза. Найти это число.
		Решить эту задачу с минимальным использованием процессорного времени. Решить на PHP и выслать рабочий код.
	
	*/

	require_once 'class_timer.php';

	const MIN = 1;			// минимальное значение в массиве
	const MAX = 1000000;	// максимальное значение в массиве
	
	function quick_sort(&$arr_common, $left, $right) : ?array {

        $index = splitting($arr_common, $left, $right);
    
        if ($left < $index - 1)  {

    		quick_sort($arr_common, $left, $index - 1);
        }

        if ($index < $right) {

        	quick_sort($arr_common, $index, $right);
        }

        return $arr_common;
	}

    function splitting(&$arr_common, $left, $right) : int {

    	$base = $arr_common[floor(($right + $left) / 2)];  	  // опорный элемент
        $i = $left;                                           // индекс левого указателя
        $j = $right;                                          // индекс правого указателя
        
        while ($i <= $j) {

            while ($arr_common[$i] < $base) {

                $i++;
            }

            while ($arr_common[$j] > $base) {

                $j--;
            }

            if ($i <= $j) {

            	[$arr_common[$i], $arr_common[$j]] = [$arr_common[$j], $arr_common[$i]]; 	// переприсваивание с использование деструктуризации

            	$i++;
                $j--;
            }
        }

        return $i;   // возвращается индекс левого указателя
    }

    try {

		$source_array = array(7, 8, 100, 200, 483175, 500000, 1000000, 100);
		
		// Убрать комментарии у строк 69, 70, 71, 72, если необходимо посмотреть работу автоматически гененируемого массива

		//$source_array = range(MIN, MAX);
		//$random_key = rand(0, count($source_array));
		//$random_value = rand(MIN, MAX);
		//array_splice($source_array, $random_key, 0, $random_value);		// вставка произвольного дублирующего элемента в произвольную позицию массива
		
		$source_array_length = count($source_array);

		shuffle($source_array);		// перемешивание элементов массива

		echo 'Выделенная память в Мб = '.round((memory_get_usage()/1024/1024), 2)."<br>";  // ~ 35 Мб выделено на PHP при 1 000 000 элементов.

		Timer::start();		// начала подсчёта времени выполнения скрипта 

	  	$sorted_array = quick_sort($source_array, 0, $source_array_length - 1);

	  	for ($i = 0; $i < $source_array_length; $i++) {
     		
 			if ($i === $source_array_length - 1) {

	  			echo '<br>Повторяющийся элемент не найден';

	  			break;

	  		} else if ($sorted_array[$i] === $sorted_array[$i+1]) {

	  			echo '<br>Повторяющийся элемент найден = '.$sorted_array[$i];
	  			
	  			break;
	  		} 
		}

		echo '<br><br>Скрипт отработал за '.Timer::finish().' секунд';		// окончание подсчёта времени выполнения скрипта

	} catch (Exception $e) {

	    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
	}
	
	/* 
		При условии непрерывности последовательности элементов массива исходную задачу можно решить без использования сортировки 
		исходного массива, приведённым ниже алгоритмом:
	*/
	
	/*

	$duplicate_element = 0;

	$source_array_length = count($source_array);

	for ($i = 0; $i < $source_array_length; $i++) {
		
  		if ($source_array[abs($source_array[$i])] > 0) {

  			$source_array[abs($source_array[$i])] = -$source_array[abs($source_array[$i])];
		
  		} else {

  			$duplicate_element = abs($source_array[$i]);
  			break;
	    }
	}

	echo '<br>Повторяющийся элемент = '.$duplicate_element;
	
	*/

		