import React from 'react';

// sendResources.js
const sendResources = async (data) => {
  try {
    console.log("Sending data:", data);

    const response = await fetch(`${process.env.REACT_APP_API_URL}/generator/generate`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      }
    );

    const result = await response.json();

    let feedback;
    if (response.ok && result.status === "ok") {
      feedback = "✅ Files generated and routes saved successfully!";
    } else {
      feedback = "❌ Failed to generate files. Please try again.";
    }

    return { result, feedback };
  } catch (err) {
    console.error(err);
    return { result: null, feedback: "❌ An error occurred. Please try again." };
  }
};

export default sendResources;



