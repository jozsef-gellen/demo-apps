<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>JSON Validátor</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 2em;
      background: #f0f0f0;
      color: #333;
    }

    textarea {
      width: 100%;
      height: 300px;
      font-family: monospace;
      font-size: 14px;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 20px;
      resize: vertical;
    }

    .result {
      font-weight: bold;
      margin-top: 10px;
    }

    .error {
      color: darkred;
    }

    .valid {
      color: green;
    }

    input[type="file"] {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <h1>JSON Validátor</h1>

  <input type="file" id="fileInput" accept=".json" />
  <br />
  <textarea id="jsonInput" placeholder="Ide másolhatod be a JSON tartalmát..."></textarea>
  <br />
  <button id="validateBtn">Ellenőrzés</button>
  <div class="result" id="result"></div>

  <script>
    const fileInput = document.getElementById("fileInput");
    const jsonInput = document.getElementById("jsonInput");
    const validateBtn = document.getElementById("validateBtn");
    const resultDiv = document.getElementById("result");

    fileInput.addEventListener("change", function () {
      const file = this.files[0];
      if (!file || !file.name.endsWith(".json")) {
        resultDiv.textContent = "Csak JSON fájlt tölthetsz fel!";
        resultDiv.className = "result error";
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        jsonInput.value = e.target.result;
        resultDiv.textContent = "";
      };
      reader.readAsText(file);

      jsonInput.value = ""; // kiürítjük a textarea-t
    });

    jsonInput.addEventListener("input", function () {
      fileInput.value = ""; // kézi beírásnál kiürítjük a fájlfeltöltést
    });

    validateBtn.addEventListener("click", function () {
      const jsonText = jsonInput.value.trim();
      resultDiv.textContent = "";

      if (!jsonText) {
        resultDiv.textContent = "Nincs JSON tartalom!";
        resultDiv.className = "result error";
        return;
      }

      try {
        JSON.parse(jsonText);
        resultDiv.textContent = "✅ A JSON érvényes.";
        resultDiv.className = "result valid";
      } catch (err) {
        // Próbáljuk meg kiszedni a hibás sort
        let errorLine = null;
        const message = err.message;
        const match = message.match(/at position (\d+)/);
        if (match && match[1]) {
          const pos = parseInt(match[1]);
          const before = jsonText.slice(0, pos);
          const line = before.split(/\r?\n/).length;
          errorLine = line;
        }

        resultDiv.textContent = "❌ Hibás JSON!"
          + (errorLine ? ` (hiba a(z) ${errorLine}. sorban)` : "")
          + `\n${err.message}`;
        resultDiv.className = "result error";
      }
    });
  </script>
</body>
</html>
