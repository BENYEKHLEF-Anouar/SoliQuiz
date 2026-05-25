# How to Run your AI QCM Automation (Quick Start Guide)

Follow these simple steps to run and test your new AI QCM Generator.

---

## 🔄 Step 1: Activate the Workflow in n8n

1. Go to your **n8n editor** in your browser: [http://localhost:5678](http://localhost:5678)
2. Open the QCM Generator workflow you created.
3. In the top-right corner of the n8n screen, click the **"Active"** toggle switch (it will turn green). This makes the webhook active and ready to receive requests from SoliQuiz.
4. *(Optional Testing)*: You can click the **"Listen for test event"** button on the Webhook node if you want to inspect a single test run.

---

## 🎨 Step 2: Use it inside SoliQuiz

1. Go to the **SoliQuiz QCM Creation page** as an Instructor/Formateur.
2. At the top of the question-builder form, you will see the new banner: **"Intelligent AI QCM Generator"**.
3. Click the **"Generate with AI"** button.
4. A popup modal will appear:
   * **Topic**: Enter the subject you want (e.g., *PHP OOP Inheritance* or *HTML Forms*).
   * **Number of questions**: Choose 3, 5, or 10.
5. Click **"Launch"**.

---

## ⚡ What happens next?

* Alpine.js will send the request to your Laravel server, which passes it to n8n on `http://localhost:5678`.
* n8n will query **Gemini 1.5 Pro** using your Google AI Pro API Key.
* Gemini will instantly generate the perfect questions, answers, points (scaling up to exactly 20), and explanations.
* The questions will automatically appear inside your SoliQuiz creation editor!
* You can review, make final changes, and save the QCM.
