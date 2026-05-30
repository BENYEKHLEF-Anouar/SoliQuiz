# Step-by-Step Integration Guide: AI Question Explainer (n8n + Gemini + Laravel)

This guide explains step-by-step how to configure **n8n** and connect it to your Google Gemini account to generate personalized pedagogical explanations for students inside **SoliQuiz**.

---

## 📌 Step 1: Run n8n on your Computer

To start your local n8n server, open your terminal and run:
```bash
npx n8n
```

Once n8n starts successfully:
1. Open your browser and go to: [http://localhost:5678](http://localhost:5678)
2. Log in to your n8n workspace.

---

## 📐 Step 2: Configure the n8n Workflow

Your workflow connects the nodes exactly like this:

```
[ Webhook (POST) ] ───► [ Code Node (JS) ] ───► [ Basic LLM Chain ] ───► [ Respond to Webhook ]
                                                       │ (Model*)
                                                 [ Google Gemini Chat Model ]
```

### 1. "Webhook" Node (Trigger)
This node receives the incorrect question context from Laravel.
* **HTTP Method**: Select `POST`.
* **Path**: Enter `ai-explain-question`.
* **Respond**: Select `Using Respond to Webhook Node` (crucial so n8n waits for the AI to finish before responding to Laravel).

---

### 2. "Code" Node (Prompt Formatter)
This node uses JavaScript to clean up the QCM data and build a perfect pedagogical prompt.
* **Name**: Change to `Prepare Prompt`.
* **Language**: JavaScript.
* **JavaScript Code**: Paste this exact snippet:
```javascript
const body = $input.first().json.body;

const question = body.question || '';
const studentName = body.student_name || 'l\'étudiant';
const qcmTitle = body.qcm_title || 'ce QCM';
const correctAnswers = (body.correct_answers || []).join(', ');
const studentAnswers = (body.student_answers || []).join(', ') || 'aucune réponse';
const allOptions = (body.all_options || []).map(o => `- ${o.texte} (${o.est_correcte ? 'CORRECTE' : 'fausse'})`).join('\n');
const explication = body.explication || '';

const prompt = `Tu es un assistant pédagogique expert de SoliQuiz, une plateforme d'évaluation créée par Solicode.

Un étudiant nommé ${studentName} a répondu incorrectement à une question du QCM "${qcmTitle}".

Question : ${question}

Toutes les options :
${allOptions}

Réponse(s) correcte(s) : ${correctAnswers}
Réponse(s) choisie(s) par l'étudiant : ${studentAnswers}
${explication ? '\nIndice pédagogique disponible : ' + explication : ''}

TA MISSION :
1. Explique pourquoi la réponse de l'étudiant est incorrecte (en 1-2 phrases directes)
2. Explique pourquoi la/les bonne(s) réponse(s) est/sont correcte(s) (en 2-3 phrases claires et pédagogiques)
3. Donne un conseil mémo ou une règle pour retenir la notion

Règles ABSOLUES :
- Réponds dans la MÊME langue que la question (si la question est en français, réponds en français ; si en anglais, en anglais ; si en darija, en darija)
- Sois encourageant et positif
- Maximum 5 phrases au total
- AUCUN emoji
- Pas de mise en forme markdown (pas de **, pas de #)
- Commence directement par l'explication`;

return [{ json: { prompt } }];
```

---

### 3. "Basic LLM Chain" Node (AI Orchestrator)
This node combines your prompt rules and the AI model.
* **Source for Prompt (User Message)**: Change this to **Define Below**.
* **Prompt (User Message)**: Enter exactly this:
  ```text
  {{ $json.prompt }}
  ```

---

### 4. "Google Gemini Chat Model" Node (The Brain)
* **Credential**: Select your Google Gemini API Key.
* **Model**: Select `gemini-2.5-flash` (or `gemini-1.5-pro`).

---

### 5. "Respond to Webhook" Node (Data Return)
This node sends the AI-generated pedagogical response back to the Laravel application.
* **Respond With**: Select `Json Query`.
* **Response Body**: Paste this exact JSON block:
```json
{
  "explanation": "{{ $json.text }}"
}
```

---

## ⚡ Direct Import (Alternative)

Instead of building it manually, you can import the pre-built workflow JSON file directly:
1. Open n8n, click the three dots in the top-right corner, and select **"Import from file"**.
2. Select the file: `d:\WebProjects\SoliQuiz\n8n-workflows\ai-explain-question.json`
3. Click **"Save"** and make sure to **"Activate"** the workflow in the top-right corner!
