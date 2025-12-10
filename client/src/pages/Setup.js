import React, { useState } from 'react';
import ModelControllerForm from '../components/ModelControllerForm';
import DatabaseForm from '../components/DatabaseForm';

function Setup() {
    const [modelControllerData, setModelControllerData] = useState({});
    const [databaseData, setDatabaseData] = useState({});
    const [step, setStep] = useState(1); // current step

    const nextStep = () => setStep(prev => prev + 1);
    const prevStep = () => setStep(prev => prev - 1);

    const saveData = () => {
        const payload = { ...modelControllerData, ...databaseData };
        console.log("Final payload:", payload);

        // Example API call
        // fetch("/api/submit", { method: "POST", body: JSON.stringify(payload) })
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
                    onSave={setDatabaseData}
                    saveData={saveData} 
                />
            )}
        </div>
    );
}

export default Setup;
