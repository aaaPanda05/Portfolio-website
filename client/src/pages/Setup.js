import React, { useState } from 'react';
import ModelControllerForm from '../components/ModelControllerForm';
import DatabaseForm from '../components/DatabaseForm';
import sendResources from "../api/sendResources.js";

function Setup() {
    const [feedback, setFeedback] = useState("");
    const [modelControllerData, setModelControllerData] = useState({});
    const [step, setStep] = useState(1);

    const nextStep = () => setStep(prev => prev + 1);
    const prevStep = () => setStep(prev => prev - 1);

    const handleDatabaseSave = async (dbPayload) => {
        const finalPayload = {
            ...modelControllerData,
            ...dbPayload,
        };

        // Optional: show immediate loading feedback
        setFeedback("⏳ Generating files, please wait...");

        try {
            const { result, feedback } = await sendResources(finalPayload);

            // Show backend result feedback
            setFeedback(feedback);

            if (result?.status === "ok") {
                prevStep();
            }

        } catch (error) {
            console.error("Generation failed:", error);
            setFeedback("❌ An unexpected error occurred. Please try again.");
        }
        };



    return (
        <div>
            {step === 1 && (
                <ModelControllerForm
                    onSave={setModelControllerData}
                    onNext={nextStep} 
                />
            )}

            {step === 2 && (
                <DatabaseForm
                    onSave={handleDatabaseSave}
                />
            )}

            {feedback && (
            <div className="fixed bottom-4 right-4 bg-neutral-800 text-white px-4 py-2 rounded-md shadow-lg animate-fade-in">
                {feedback}
                <button
                onClick={() => setFeedback("")}
                className="ml-2 text-red-400 hover:text-red-500"
                >
                </button>
            </div>
            )}
        </div>
    );
}

export default Setup;
