<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connect Boxes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      width: 100%;
      height: 80vh;
      position: relative;
      border: 2px solid #ccc;
      margin-top: 20px;
    }

    .box {
      width: 100px;
      height: 50px;
      border-radius: 5px;
      background-color: lightblue;
      border: 2px solid #333;
      position: absolute;
      cursor: move;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .box.dragging {
      opacity: 0.5;
    }

    #controls {
      margin-top: 20px;
    }

    .context-menu {
      position: absolute;
      background-color: white;
      border: 1px solid #ccc;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      display: none;
      flex-direction: column;
    }

    .context-menu button {
      padding: 8px 12px;
      border: none;
      background-color: #f8f8f8;
      cursor: pointer;
    }

    .context-menu button:hover {
      background-color: #ddd;
    }

    canvas {
      position: absolute;
      top: 0;
      left: 0;
      z-index: -1;
    }

    .highlighted {
      stroke: red;
      stroke-width: 4;
    }

    #stationSelect {
      display: none;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="container" id="boxContainer">
    <!-- Boxes will be added here dynamically -->
  </div>

  <div id="controls">
    <button onclick="showStationSelect()">Create Box</button>
    <select id="stationSelect" onchange="createBox(this.value)">
      <!-- Station names will be added here dynamically -->
    </select>
  </div>

  <div id="contextMenu" class="context-menu">
    <button onclick="deleteBox()">Delete Box</button>
    <button onclick="disconnectConnection()">Disconnect</button>
  </div>

  <canvas id="lineCanvas"></canvas>

  <script>
    let boxCount = 0;
    let currentBox = null;
    let selectedBoxes = [];
    let allBoxes = [];
    let connections = [];
    let highlightedConnection = null;

    const canvas = document.getElementById('lineCanvas');
    const ctx = canvas.getContext('2d');
    
    // Fetching stations from the backend
    async function fetchStations() {
      try {
        const response = await fetch('/stations');
        const stations = await response.json();
        return stations;
      } catch (error) {
        console.error('Error fetching stations:', error);
        return [];
      }
    }

    // Show station select dropdown
    function showStationSelect() {
      const stationSelect = document.getElementById('stationSelect');
      stationSelect.style.display = 'inline';
      fetchStations().then(stations => {
        stationSelect.innerHTML = '';  // Clear previous options
        const defaultOption = document.createElement('option');
        defaultOption.textContent = 'Select a Station';
        defaultOption.value = '';
        stationSelect.appendChild(defaultOption);

        stations.forEach(station => {
          const option = document.createElement('option');
          option.value = station.stationname;
          option.textContent = station.stationname;
          stationSelect.appendChild(option);
        });
      });
    }

    // Create box based on selected station
    async function createBox(stationName) {
      if (!stationName) return;

      // Check if a box with the same station name already exists
      if (allBoxes.some(box => box.textContent.includes(stationName))) {
        alert(`Box for station "${stationName}" already exists.`);
        return;
      }

      const box = document.createElement('div');
      box.classList.add('box');
      box.setAttribute('draggable', true);
      box.textContent = `${stationName} (${boxCount + 1})`;
      box.id = `box-${boxCount + 1}`;
      box.dataset.sequence = boxCount + 1;
      box.style.left = `${Math.random() * 90}vw`;
      box.style.top = `${Math.random() * 70}vh`;

      allBoxes.push(box);
      boxCount++;

      box.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text', box.id);
        setTimeout(() => box.classList.add('dragging'), 0);
      });

      box.addEventListener('dragend', () => {
        box.classList.remove('dragging');
        updateConnections();
      });

      box.addEventListener('click', (e) => {
        e.stopPropagation();
        currentBox = box;
        showContextMenu(e.clientX, e.clientY);
      });

      document.getElementById('boxContainer').appendChild(box);

      // Hide the station select after creating the box
      document.getElementById('stationSelect').style.display = 'none';
    }

    // Drag over the box container
    document.getElementById('boxContainer').addEventListener('dragover', (e) => {
      e.preventDefault();
      const draggingBox = document.querySelector('.dragging');
      const container = document.getElementById('boxContainer');
      const mouseX = e.clientX - container.offsetLeft;
      const mouseY = e.clientY - container.offsetTop;

      draggingBox.style.left = `${mouseX - 50}px`;
      draggingBox.style.top = `${mouseY - 50}px`;
    });

    // Show context menu with options for the selected box
    async function showContextMenu(x, y) {
      const contextMenu = document.getElementById('contextMenu');
      contextMenu.style.left = `${x}px`;
      contextMenu.style.top = `${y}px`;

      const availableBoxes = allBoxes.filter(box => box !== currentBox);
      contextMenu.innerHTML = '<button onclick="deleteBox()">Delete Box</button>';

      // Add connect buttons for available boxes
      availableBoxes.forEach(box => {
        const connectButton = document.createElement('button');
        connectButton.textContent = `Connect to ${box.textContent}`;
        connectButton.onclick = () => connectBoxes(currentBox, box);
        contextMenu.appendChild(connectButton);
      });

      // Add disconnect buttons for existing connections
      const connectedBoxes = connections.filter(connection => connection.box1 === currentBox || connection.box2 === currentBox);
      connectedBoxes.forEach(connection => {
        const disconnectButton = document.createElement('button');
        disconnectButton.textContent = `Disconnect from ${connection.box1 === currentBox ? connection.box2.textContent : connection.box1.textContent}`;
        disconnectButton.onclick = () => disconnectBoxes(connection);
        contextMenu.appendChild(disconnectButton);
      });

      contextMenu.style.display = 'flex';
    }

    // Close context menu when clicking elsewhere
    document.body.addEventListener('click', () => {
      document.getElementById('contextMenu').style.display = 'none';
    });

    // Delete a box
    function deleteBox() {
      if (currentBox) {
        const deletedSequence = parseInt(currentBox.dataset.sequence);
        currentBox.remove();
        allBoxes = allBoxes.filter(box => box !== currentBox);
        connections = connections.filter(connection => connection.box1 !== currentBox && connection.box2 !== currentBox);
        updateSequenceNumbers(deletedSequence);
        updateConnections();
        document.getElementById('contextMenu').style.display = 'none';
      }
    }

    // Update sequence numbers after deletion
    function updateSequenceNumbers(deletedSequence) {
      allBoxes.forEach((box, index) => {
        const sequence = index + 1;
        box.textContent = box.textContent.split('(')[0] + ` (${sequence})`; // Update text content
        box.dataset.sequence = sequence; // Update dataset
      });
    }

    // Connect two boxes
    function connectBoxes(box1, box2) {
      // Check if a connection already exists in the direction box1 -> box2
      const existingConnection = connections.find(connection =>
        (connection.box1 === box1 && connection.box2 === box2)
      );

      // If a connection from box1 to box2 already exists, return without doing anything
      if (existingConnection) {
        alert('Connection from this box to the selected box already exists.');
        return;
      }

      const box1Rect = box1.getBoundingClientRect();
      const box2Rect = box2.getBoundingClientRect();

      const box1Edge = {
        x: box1Rect.left + box1Rect.width / 2,
        y: box1Rect.top + box1Rect.height / 2
      };

      const box2Edge = {
        x: box2Rect.left + box2Rect.width / 2,
        y: box2Rect.top + box2Rect.height / 2
      };

      connections.push({ box1, box2, box1Edge, box2Edge });

      updateConnections();
      document.getElementById('contextMenu').style.display = 'none';
    }

    // Disconnect two boxes
    function disconnectBoxes(connection) {
      connections = connections.filter(conn => conn !== connection);
      updateConnections();
      document.getElementById('contextMenu').style.display = 'none';
    }

    // Draw the connection between boxes
    function drawArrow(x1, y1, x2, y2, highlighted=false) {
      const arrowSize = 10;
      const angle = Math.atan2(y2 - y1, x2 - x1);

      const factor = 0.5;

      const arrowEndX = x1 + (x2 - x1) * factor;
      const arrowEndY = y1 + (y2 - y1) * factor;

      ctx.beginPath();
      ctx.moveTo(arrowEndX, arrowEndY);
      ctx.lineTo(arrowEndX - arrowSize * Math.cos(angle - Math.PI / 6), arrowEndY - arrowSize * Math.sin(angle - Math.PI / 6));
      ctx.moveTo(arrowEndX, arrowEndY);
      ctx.lineTo(arrowEndX - arrowSize * Math.cos(angle + Math.PI / 6), arrowEndY - arrowSize * Math.sin(angle + Math.PI / 6));
      ctx.strokeStyle = highlighted ? 'red' : 'green';
      ctx.lineWidth = 2;
      ctx.stroke();
    }

    // Update connections after each change
    function updateConnections() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      connections.forEach(connection => {
        const { box1, box2, box1Edge, box2Edge } = connection;

        const box1Rect = box1.getBoundingClientRect();
        const box2Rect = box2.getBoundingClientRect();

        connection.box1Edge = {
          x: box1Rect.left + box1Rect.width / 2,
          y: box1Rect.top + box1Rect.height / 2
        };

        connection.box2Edge = {
          x: box2Rect.left + box2Rect.width / 2,
          y: box2Rect.top + box2Rect.height / 2
        };

        ctx.beginPath();
        ctx.moveTo(connection.box1Edge.x, connection.box1Edge.y);
        ctx.lineTo(connection.box2Edge.x, connection.box2Edge.y);
        ctx.strokeStyle = '#333';
        ctx.lineWidth = 2;
        ctx.stroke();

        drawArrow(connection.box1Edge.x, connection.box1Edge.y, connection.box2Edge.x, connection.box2Edge.y, connection === highlightedConnection);
      });

      highlightedConnection = null;
    }

    window.addEventListener('resize', () => {
      canvas.width = document.getElementById('boxContainer').offsetWidth;
      canvas.height = document.getElementById('boxContainer').offsetHeight;
      updateConnections();
    });

    canvas.width = document.getElementById('boxContainer').offsetWidth;
    canvas.height = document.getElementById('boxContainer').offsetHeight;
  </script>
</body>
</html>
