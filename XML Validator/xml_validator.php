<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>XML Validátor</title>
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
  <h1 class="multilang" data-text_hu="" data-text_en="">XML Validátor</h1>

  <input type="file" id="fileInput" accept=".xml" />
  <br />
  <textarea id="xmlInput"  class="multilang" data-text_hu="Ide másolhatod be az XML tartalmát..." data-text_en="Paste the XML content..." placeholder="Ide másolhatod be az XML tartalmát..."></textarea>
  <br />
  <button id="validateBtn"  class="multilang" data-text_hu="Ellenőrzés" data-text_en="Check">Ellenőrzés</button>
  <div class="result" id="result"></div>

  <script>
    const fileInput = document.getElementById("fileInput");
    const xmlInput = document.getElementById("xmlInput");
    const validateBtn = document.getElementById("validateBtn");
    const resultDiv = document.getElementById("result");

    fileInput.addEventListener("change", function () {
      const file = this.files[0];
      if (!file || !file.name.endsWith(".xml")) {
        resultDiv.textContent = "Csak XML fájlt tölthetsz fel!";
        resultDiv.className = "result error";
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        xmlInput.value = e.target.result;
        resultDiv.textContent = "";
      };
      reader.readAsText(file);

      xmlInput.value = ""; // kiürítjük a textarea-t
    });

    xmlInput.addEventListener("input", function () {
      fileInput.value = ""; // ha kézzel írják be, akkor kiürítjük a file inputot
    });

   validateBtn.addEventListener("click", function () {
  const xmlText = xmlInput.value.trim();
  resultDiv.textContent = "";

  if (!xmlText) {
    resultDiv.textContent = "Nincs XML tartalom!";
    resultDiv.className = "result error";
    return;
  }

  const parser = new DOMParser();
  const parsed = parser.parseFromString(xmlText, "application/xml");
  const parserError = parsed.getElementsByTagName("parsererror");

  if (parserError.length > 0) {
    // manuálisan próbáljuk kideríteni a hibás sort
    const lines = xmlText.split(/\r?\n/);
    let errorLine = null;

    for (let i = 0; i < lines.length; i++) {
      const subtext = lines.slice(0, i + 1).join("\n");
      const testParse = parser.parseFromString(subtext, "application/xml");
      if (testParse.getElementsByTagName("parsererror").length > 0) {
        errorLine = i + 1; // emberi olvasás szerint 1-től számozunk
        break;
      }
    }

    resultDiv.textContent = "❌ Hibás XML!"
      + (errorLine ? ` (hiba a(z) ${errorLine}. sorban)` : "");
    resultDiv.className = "result error";
  } else {
    resultDiv.textContent = "✅ Az XML szerkezetileg érvényes.";
    resultDiv.className = "result valid";
  }
});

  </script>
</body>
</html>
