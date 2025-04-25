<?php
class AlgorithmModel
{
  private function swap(&$array, $index1, $index2)
  {
    $temp = $array[$index1];
    $array[$index1] = $array[$index2];
    $array[$index2] = $temp;
  }

  public function getSteps($algorithm, $array)
  {
    $steps = [];

    switch ($algorithm) {
      case 'bubble':
        $steps = $this->bubbleSortSteps($array);
        break;
      case 'insertion':
        $steps = $this->insertionSortSteps($array);
        break;
      case 'quick':
        $steps = $this->quickSortSteps($array);
        break;
      case 'heap':
        $steps = $this->heapSortSteps($array);
        break;
      case 'merge':
        $steps = $this->mergeSortSteps($array);
        break;
      default:
        return [];
    }

    $steps[] = [
      'array' => array_values($array),
      'compare' => []
    ];

    return $steps;
  }

  private function bubbleSortSteps(&$array)
  {
    $steps = [];
    $n = count($array);
    $maxSteps = 5000; // Limit for Bubble Sort

    for ($i = 0; $i < $n - 1; $i++) {
      for ($j = 0; $j < $n - $i - 1; $j++) {
        $steps[] = [
          'array' => array_values($array),
          'compare' => [$j, $j + 1]
        ];

        if ($array[$j] > $array[$j + 1]) {
          $this->swap($array, $j, $j + 1);

          $steps[] = [
            'array' => array_values($array),
            'compare' => [$j, $j + 1]
          ];
        }

        if (count($steps) > $maxSteps) {
          return $steps; // Stop if we exceed the step limit
        }
      }
    }

    return $steps;
  }

  private function insertionSortSteps(&$array)
  {
    $steps = [];
    $n = count($array);

    for ($i = 1; $i < $n; $i++) {
      $key = $array[$i];
      $j = $i - 1;

      while ($j >= 0 && $array[$j] > $key) {
        $steps[] = [
          'array' => array_values($array),
          'compare' => [$j, $j + 1]
        ];

        $array[$j + 1] = $array[$j];
        $j--;

        $steps[] = [
          'array' => array_values($array),
          'compare' => [$j + 1, $j + 2]
        ];
      }

      $array[$j + 1] = $key;
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$j + 1, $i]
      ];
    }

    return $steps;
  }

  private function quickSortSteps(&$array)
  {
    $steps = [];
    $this->quickSortHelper($array, 0, count($array) - 1, $steps);
    return $steps;
  }

  private function quickSortHelper(&$array, $low, $high, &$steps)
  {
    if ($low < $high) {
      $pi = $this->partition($array, $low, $high, $steps);
      $this->quickSortHelper($array, $low, $pi - 1, $steps);
      $this->quickSortHelper($array, $pi + 1, $high, $steps);
    }
  }

  private function partition(&$array, $low, $high, &$steps)
  {
    $pivot = $array[$high];
    $i = $low - 1;

    for ($j = $low; $j < $high; $j++) {
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$j, $high]
      ];

      if ($array[$j] < $pivot) {
        $i++;
        $this->swap($array, $i, $j);

        $steps[] = [
          'array' => array_values($array),
          'compare' => [$i, $j]
        ];
      }
    }

    $this->swap($array, $i + 1, $high);

    $steps[] = [
      'array' => array_values($array),
      'compare' => [$i + 1, $high]
    ];

    return $i + 1;
  }

  private function heapSortSteps(&$array)
  {
    $steps = [];
    $n = count($array);

    for ($i = floor($n / 2) - 1; $i >= 0; $i--) {
      $this->heapify($array, $n, $i, $steps);
    }

    for ($i = $n - 1; $i > 0; $i--) {
      $steps[] = [
        'array' => array_values($array),
        'compare' => [0, $i]
      ];

      $this->swap($array, 0, $i);

      $steps[] = [
        'array' => array_values($array),
        'compare' => [0, $i]
      ];

      $this->heapify($array, $i, 0, $steps);
    }

    return $steps;
  }

  private function heapify(&$array, $n, $i, &$steps)
  {
    $largest = $i;
    $left = 2 * $i + 1;
    $right = 2 * $i + 2;

    if ($left < $n) {
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$largest, $left]
      ];
      if ($array[$left] > $array[$largest]) {
        $largest = $left;
      }
    }

    if ($right < $n) {
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$largest, $right]
      ];
      if ($array[$right] > $array[$largest]) {
        $largest = $right;
      }
    }

    if ($largest != $i) {
      $this->swap($array, $i, $largest);

      $steps[] = [
        'array' => array_values($array),
        'compare' => [$i, $largest]
      ];

      $this->heapify($array, $n, $largest, $steps);
    }
  }

  private function mergeSortSteps(&$array)
  {
    $steps = [];
    $this->mergeSortHelper($array, 0, count($array) - 1, $steps);
    return $steps;
  }

  private function mergeSortHelper(&$array, $left, $right, &$steps)
  {
    if ($left < $right) {
      $mid = floor(($left + $right) / 2);
      $this->mergeSortHelper($array, $left, $mid, $steps);
      $this->mergeSortHelper($array, $mid + 1, $right, $steps);
      $this->merge($array, $left, $mid, $right, $steps);
    }
  }

  private function merge(&$array, $left, $mid, $right, &$steps)
  {
    $leftArray = array_slice($array, $left, $mid - $left + 1);
    $rightArray = array_slice($array, $mid + 1, $right - $mid);

    $i = 0;
    $j = 0;
    $k = $left;

    while ($i < count($leftArray) && $j < count($rightArray)) {
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$left + $i, $mid + 1 + $j]
      ];

      if ($leftArray[$i] <= $rightArray[$j]) {
        $array[$k] = $leftArray[$i];
        $i++;
      } else {
        $array[$k] = $rightArray[$j];
        $j++;
      }
      $k++;

      $steps[] = [
        'array' => array_values($array),
        'compare' => [$k - 1, $k - 1]
      ];
    }

    while ($i < count($leftArray)) {
      $array[$k] = $leftArray[$i];
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$k, $k]
      ];
      $i++;
      $k++;
    }

    while ($j < count($rightArray)) {
      $array[$k] = $rightArray[$j];
      $steps[] = [
        'array' => array_values($array),
        'compare' => [$k, $k]
      ];
      $j++;
      $k++;
    }
  }
}
