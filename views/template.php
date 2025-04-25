<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Algorithm Visualizer - Made By Roee Bloch</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.2/p5.min.js"></script>
</head>

<body>
  <div class="container">
    <div class="sidebar">
      <h2>Algorithm Visualizer // Made By Roee Bloch</h2>
      <p>Current Algorithm: <span id="current-algorithm">None</span></p>
      <ul>
        <li><button onclick="selectAlgorithm('quick')">Quick Sort</button></li>
        <li><button onclick="selectAlgorithm('bubble')">Bubble Sort</button></li>
        <li><button onclick="selectAlgorithm('heap')">Heapsort</button></li>
        <li><button onclick="selectAlgorithm('insertion')">Insertion Sort</button></li>
        <li><button onclick="selectAlgorithm('merge')">Merge Sort</button></li>
      </ul>
      <div class="manual-array">
        <h3>Manual Array Input</h3>
        <textarea id="manual-array-input" placeholder="Enter array values (comma-separated)"></textarea>
        <button onclick="updateManualArray()">Update Array</button>
      </div>
    </div>
    <div class="main">
      <div id="sketch-holder"></div>
      <div class="controls">
        <button onclick="play()">▶</button>
        <button onclick="pause()">⏸</button>
        <button onclick="reset()">🔄</button>
        <label for="array-size">Size:</label>
        <input type="range" id="array-size" min="10" max="200" value="200" oninput="updateArraySize(this.value)">
        <span id="size-label">200</span>
        <label for="speed-slider">Speed:</label>
        <input type="range" id="speed-slider" min="1" max="30" value="15" oninput="updateSpeed(this.value)">
        <span id="speed-label">15</span>
      </div>
    </div>
  </div>
  <script src="assets/script.js"></script>
</body>

</html>