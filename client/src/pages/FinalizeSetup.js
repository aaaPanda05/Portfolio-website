import { useEffect, useState } from "react";
import systemApi from "../services/systemApi";

function FinalizeSetup() {
    const [systemState, setSystemState] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [password, setPassword] = useState("");
    const [ready, setReady] = useState(false);
    const [submitSuccess, setSubmitSuccess] = useState(false);

    // Fetch system state on mount
    useEffect(() => {
        const fetchStatus = async () => {
        try {
            const status = await systemApi.getStatus();
            console.log("System status:", status);
            setSystemState(status);
        } catch (err) {
            console.error("Failed to fetch system status:", err);
            setError("Unable to fetch system status");
        } finally {
            setLoading(false);
        }
        };

        fetchStatus();
    }, []);



    // Handle finalize submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!ready || !password) return;

        try {
            setLoading(true);
            await systemApi.finalizeSetup(password); // call backend to lock system
            setSubmitSuccess(true);
            setSystemState({ ...systemState, locked: true, status: "finalized" });
        } catch (err) {
            console.error("Finalize failed:", err);
            setError("Failed to finalize setup");
        } finally {
            
        }
    };

    // Render loading / error states first
    if (loading) return <div>Loading system status...</div>;
    if (error) return <div>Error: {error}</div>;

    // If system is not initialized, redirect or show warning
    if (systemState?.status === "not_initialized")
        return <div>Please complete setup first</div>;

    // If already finalized, redirect to admin login / panel
    if (systemState?.status === "finalized")
        return <div>System already finalized. Please log in to admin panel.</div>;

    // Finalize form
    return (
        <div className="min-h-screen flex items-center justify-center px-4">
            <div className="w-full max-w-md rounded-2xl border border-neutral-800 bg-neutral-900 p-8 shadow-lg">
                <h1 className="text-2xl font-semibold text-white mb-2">
                Finalize Setup
                </h1>

                <p className="text-sm text-neutral-400 mb-6">
                Confirm you are ready to enable the admin panel.
                </p>

                <form onSubmit={handleSubmit} className="space-y-6">
                {/* Password */}
                <div>
                    <label className="block text-sm font-medium text-neutral-300 mb-2">
                    Admin password
                    </label>
                    <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                    className="w-full rounded-lg bg-neutral-800 border border-neutral-700 px-4 py-2 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Enter admin password"
                    />
                </div>

                {/* Confirmation checkbox */}
                <div className="flex items-start gap-3">
                    <input
                    type="checkbox"
                    checked={ready}
                    onChange={(e) => setReady(e.target.checked)}
                    className="mt-1 h-4 w-4 rounded border-neutral-600 bg-neutral-800 text-blue-500 focus:ring-blue-500"
                    />
                    <label className="text-sm text-neutral-300">
                    I confirm I am ready to finalize the system
                    </label>
                </div>

                {/* Submit button */}
                <button
                    type="submit"
                    disabled={!ready || !password}
                    className="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition
                            hover:bg-blue-500
                            disabled:bg-neutral-700 disabled:text-neutral-400 disabled:cursor-not-allowed"
                >
                    Finalize Setup
                </button>
                </form>

                {submitSuccess && (
                <p className="mt-6 text-sm text-green-400 text-center">
                    System finalized! You can now log in to the admin panel.
                </p>
                )}
            </div>
            </div>

    );
}

export default FinalizeSetup;
