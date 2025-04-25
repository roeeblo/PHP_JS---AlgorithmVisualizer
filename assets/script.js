let array = [];
let steps = [];
let currentStep = 0;
let isPlaying = false;
let arraySize = 200;
let animationSpeed = 15;
let frameCount = 0;
let currentAlgorithm = '';
let isUpdating = false;

function setup() {
  let canvas = createCanvas(windowWidth - 250, windowHeight - 100);
  canvas.parent('sketch-holder');
  frameRate(60);
  resetEverything();
  windowResized();
}

function windowResized() {
  const sidebarWidth = 250;
  const controlsHeight = 50;
  const availableWidth = windowWidth - sidebarWidth;
  resizeCanvas(availableWidth, windowHeight - controlsHeight);

  redraw();
}

function generateArray(size) {
  array = [];
  for (let i = 0; i < size; i++) {
    array.push(random(10, height - 50));
  }
}

function draw() {
  if (isUpdating) return;
  background(255);
  const minBarWidth = 1;
  let barWidth = max(width / array.length, minBarWidth);

  const maxBarsVisible = floor(width / minBarWidth);
  let visibleArray = array;
  if (array.length > maxBarsVisible) {
    const step = ceil(array.length / maxBarsVisible);
    visibleArray = [];
    for (let i = 0; i < array.length; i += step) {
      visibleArray.push(array[i]);
    }
    barWidth = width / visibleArray.length;
  }

  if (isPlaying && steps.length > 0) {
    frameCount++;
    if (frameCount >= animationSpeed) {
      frameCount = 0;
      currentStep++;
      if (currentStep >= steps.length) {
        currentStep = steps.length - 1;
        isPlaying = false;
      }
      if (steps[currentStep]) {
        array = steps[currentStep].array;
      }
    }
  }

  for (let i = 0; i < visibleArray.length; i++) {
    if (steps.length > 0 && steps[currentStep] && steps[currentStep].compare) {
      const originalIndex = Math.floor(i * (array.length / visibleArray.length));
      if (steps[currentStep].compare.includes(originalIndex)) {
        fill(255, 0, 0);
      } else {
        fill(0, 123, 255);
      }
    } else {
      fill(0, 123, 255);
    }
    rect(i * barWidth, height - visibleArray[i], barWidth - 1, visibleArray[i]);
  }
}

async function selectAlgorithm(algorithm) {
  if (isUpdating) return;
  isUpdating = true;
  try {
    isPlaying = false;
    currentStep = 0;
    steps = [];
    currentAlgorithm = algorithm;
    document.getElementById('current-algorithm').innerText = algorithm.charAt(0).toUpperCase() + algorithm.slice(1);

    let arrayString = JSON.stringify(array);
    let response = await fetch(`index.php?action=getSteps&algorithm=${algorithm}&array=${encodeURIComponent(arrayString)}`);

    steps = await response.json();
    if (steps.length > 0) {
      array = steps[0].array;
    }
    isPlaying = true;
  } finally {
    isUpdating = false;
  }
}

function play() {
  if (steps.length > 0) {
    isPlaying = true;
  }
}

function pause() {
  isPlaying = false;
}

function reset() {
  resetEverything();
}

function resetAnimation() {
  isPlaying = false;
  currentStep = 0;
  steps = [];
  redraw();
}

function resetEverything() {
  isUpdating = true;
  try {
    resetAnimation();
    generateArray(arraySize);
    if (currentAlgorithm) {
      selectAlgorithm(currentAlgorithm);
    }
  } finally {
    isUpdating = false;
  }
}

function updateArraySize(value) {
  if (isUpdating) return;

  arraySize = parseInt(value);
  document.getElementById('size-label').innerText = arraySize;
  resetEverything();
}

function updateSpeed(value) {
  const minSpeed = 1;
  const maxSpeed = 30;
  animationSpeed = maxSpeed - (value - minSpeed);
  document.getElementById('speed-label').innerText = value;
}

async function updateManualArray() {
  if (isUpdating) return;
  isUpdating = true;

  try {
    const input = document.getElementById('manual-array-input').value;
    let newArray = input.split(',').map(Number).filter(num => !isNaN(num));

    if (newArray.length > 0) {
      // Scale the manually entered values
      newArray = newArray.map(value => value * 50);
      array = newArray;
      arraySize = newArray.length;
      resetAnimation();
      if (currentAlgorithm) {
        await selectAlgorithm(currentAlgorithm);
      } else {
        redraw();
      }
    } else {
      alert("Invalid array input. Please enter comma-separated numbers.");
    }
  } finally {
    isUpdating = false;
  }
}