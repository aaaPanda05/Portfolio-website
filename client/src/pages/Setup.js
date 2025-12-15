import React, { useState } from 'react';
import ModelControllerForm from '../components/ModelControllerForm';
import DatabaseForm from '../components/DatabaseForm';

function Setup() {
    const [modelControllerData, setModelControllerData] = useState({});
    const [step, setStep] = useState(1); // current step

    const nextStep = () => setStep(prev => prev + 1);

    const handleDatabaseSave = (dbPayload) => {
        const finalPayload = {
            ...modelControllerData,
            ...dbPayload,
        };

        console.log("Final payload:", finalPayload);
        // sendResources(finalPayload);
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
        </div>
    );
}

export default Setup;
