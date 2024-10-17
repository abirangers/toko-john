import { Button } from "@/Components/ui/button";
import MainLayout from "@/Layouts/MainLayout";
import { Order, User } from "@/types";
import { router } from "@inertiajs/react";
import React, { useEffect } from "react";

interface IndexPaymentProps {
    order: Order;
    auth: {
        user: User;
    };
    snapToken: string;
}

const IndexPayment: React.FC<IndexPaymentProps> = ({
    order,
    auth,
    snapToken,
}): React.ReactNode => {
    useEffect(() => {
        const midtransScriptUrl =
            "https://app.sandbox.midtrans.com/snap/snap.js";

        let scriptTag = document.createElement("script");
        scriptTag.src = midtransScriptUrl;

        const myMidtransClientKey = import.meta.env.VITE_MIDTRANS_CLIENT_KEY;
        if (myMidtransClientKey) {
            scriptTag.setAttribute("data-client-key", myMidtransClientKey);
        }

        scriptTag.onload = () => {
            console.log("Midtrans script loaded successfully");
        };

        scriptTag.onerror = (error) => {
            console.error("Error loading Midtrans script:", error);
        };

        document.body.appendChild(scriptTag);

        return () => {
            document.body.removeChild(scriptTag);
        };
    }, []);

    const handlePay = () => {
        console.log("Attempting to initialize Snap payment");
        console.log("Snap Token:", snapToken);

        if (typeof window.snap === "undefined") {
            console.error(
                "Snap object is not available. Midtrans script might not be loaded correctly."
            );
            return;
        }

        if (!snapToken) {
            console.error("Snap Token is missing or invalid");
            return;
        }

        window.snap.pay(snapToken, {
            onSuccess: function (result: any) {
                router.put(
                    route("payment.success", {
                        order_code: order.order_code,
                    })
                );
            },
            onPending: function (result: any) {
                router.put(
                    route("payment.pending", {
                        order_code: order.order_code,
                    })
                );
            },
            onError: function (result: any) {
                console.error("Payment error:", result);
                console.error("Detail error:", JSON.stringify(result, null, 2));
                alert("Payment error: " + result.status_message);
            },
            onClose: function () {
                console.log("Payment popup closed");
            },
        });
    };

    return (
        <MainLayout user={auth.user}>
            <h2 className="my-8 mb-6 text-5xl font-semibold tracking-tight text-center text-primary">
                Payment
            </h2>
            <div className="max-w-lg p-8 mx-auto mt-2 bg-white rounded-lg shadow-md text-secondary">
                <div className="flex justify-center mb-6 max-w-[500px] h-[300px]">
                    <img
                        src="/images/checkout-image.jpg"
                        alt="Payment Illustration"
                        className="object-cover w-full"
                    />
                </div>
                <div className="mb-4">
                    <strong>Order ID:</strong> {order.order_code}
                </div>
                <div className="mb-4">
                    <strong>Product Names:</strong>{" "}
                    {order.order_items
                        .map((item) => item.product.title)
                        .join(", ")}
                </div>
                <div className="mb-4">
                    <strong>Customer Name:</strong> {order.user.name}
                </div>
                <div className="mb-4">
                    <strong>Customer Email:</strong> {order.user.email}
                </div>
                <div className="mb-4">
                    <strong>Total Payment:</strong> Rp {order.total_price}
                </div>
                <Button className="w-full" onClick={handlePay}>
                    Pay Now
                </Button>
                <div id="snap-container"></div>
            </div>
        </MainLayout>
    );
};

export default IndexPayment;
