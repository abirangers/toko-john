import { Button } from "@/Components/ui/button";
import { CartItem } from "@/types";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { formatPrice } from "@/lib/utils";

const OrderSummary = ({
    orderTotal,
    cartItems,
    form,
    handleSubmit,
    isOpen,
    setIsOpen,
}: {
    orderTotal: number;
    cartItems: CartItem[];
    form: any;
    handleSubmit: any;
    isOpen: boolean;
    setIsOpen: any;
}) => {
    

    return (
        <>
            <Dialog open={isOpen} onOpenChange={setIsOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle className="flex justify-center">
                            <video className="outline-none" autoPlay>
                                <source
                                    src="animation.mp4"
                                    type="video/mp4"
                                    className={`border-none`}
                                />
                                Your browser does not support the video tag
                            </video>
                        </DialogTitle>
                        <DialogDescription className="text-base text-center">
                            <h2 className="mb-2 text-xl font-semibold text-gray-800">
                                Order Successful!
                            </h2>
                            <p className="mb-4 text-gray-600">
                                Thank you for your order! Your purchase was
                                successful. We will send you an email
                                notification once your items are ready for
                                shipment. If you have any questions, feel free
                                to contact our support team. Enjoy your shopping
                                experience with us!
                            </p>
                        </DialogDescription>
                    </DialogHeader>
                </DialogContent>
            </Dialog>

            <div className="p-8 rounded-md bg-gray-50">
                <h2 className="mb-6 text-lg font-medium">Order Summary</h2>
                <hr />
                <div className="flex justify-between my-4">
                    <h3 className="text-base font-medium">Order total</h3>
                    <h3 className="text-base font-normal">
                        {formatPrice(orderTotal)}
                    </h3>
                </div>
                <Button
                    onClick={(e) => handleSubmit(e)}
                    className="w-full hover:before:-translate-x-[464px]"
                    disabled={
                        cartItems.length < 1 ||
                        form.processing
                    }
                >
                    Checkout
                </Button>
            </div>
        </>
    );
};

export default OrderSummary;
