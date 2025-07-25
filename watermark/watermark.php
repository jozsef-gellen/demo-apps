<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Kép Vízjelező</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 2em;
      background: #f4f4f4;
      color: #333;
      text-align: center;
    }
    input {
      margin: 10px;
    }
    button {
      padding: 10px 20px;
      font-size: 1em;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>Gyors vízjelezés</h1> 
    <p>Ebben a demó verzióban csak egy kép vízjelezhető, adott a vízjel mérete 10%, opozíciója középen, áttetszősége 20%.</p>
  <div>
    <label>Vízjel (png):</label>
    <input type="file" id="watermarkInput" accept=".png" />
  </div>

  <div>
    <label>Kép (jpg/png):</label>
    <input type="file" id="imageInput" accept=".png,.jpg,.jpeg" />
  </div>

  <button id="processBtn">Vízjelezés</button>

  <canvas id="canvas" style="display:none;"></canvas>

  <script>
    const watermarkInput = document.getElementById("watermarkInput");
    const imageInput = document.getElementById("imageInput");
    const processBtn = document.getElementById("processBtn");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");

    processBtn.addEventListener("click", async () => {
      const watermarkFile = watermarkInput.files[0];
      const mainImageFile = imageInput.files[0];

      if (!watermarkFile || !mainImageFile) {
        alert("Mindkét képfájlt ki kell választani!");
        return;
      }

      if (!watermarkFile.name.endsWith(".png")) {
        alert("A vízjel csak PNG lehet!");
        return;
      }

      const loadImage = (file) => {
        return new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.onload = function (e) {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = e.target.result;
          };
          reader.readAsDataURL(file);
        });
      };

      try {
        const mainImg = await loadImage(mainImageFile);
        const watermarkImg = await loadImage(watermarkFile);

        canvas.width = mainImg.width;
        canvas.height = mainImg.height;

        // Kirajzoljuk az alapképet
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(mainImg, 0, 0);

        // Méretezzük a vízjelet
        const targetWidth = mainImg.width / 3;
        const scale = targetWidth / watermarkImg.width;
        const targetHeight = watermarkImg.height * scale;

        const x = (mainImg.width - targetWidth) / 2;
        const y = (mainImg.height - targetHeight) / 2;

        ctx.globalAlpha = 0.2; // 10% átlátszóság
        ctx.drawImage(watermarkImg, x, y, targetWidth, targetHeight);
        ctx.globalAlpha = 1.0;

        // Automatikus letöltés
        const output = canvas.toDataURL("image/png");
        const a = document.createElement("a");
        a.href = output;
        a.download = "vizjelezett_kep.png";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

         // Újraindítás inputok kiürítésével
    watermarkInput.value = "";
    imageInput.value = "";

      } catch (error) {
        alert("Hiba történt a képek feldolgozása közben.");
        console.error(error);
      }
    });
  </script>
</body>
</html>
