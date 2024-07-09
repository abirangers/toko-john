import { Button } from "@/Components/ui/button";
import { CartItem } from "@/types";
import { formatPrice } from "@/lib/utils";

const CartSummary = ({
    orderTotal,
    cartItems,
    handleSubmit,
    isDisabled,
}: {
    orderTotal: number;
    cartItems: CartItem[];
    handleSubmit: any;
    isDisabled: boolean;
}) => {
    return (
        <>
            <div className="p-8 bg-white border rounded-md shadow-sm">
                <h2 className="mb-6 text-lg font-medium text-center text-primary">
                    Order Summary
                </h2>
                <hr />
                <div className="flex justify-between my-4">
                    <h3 className="text-base font-medium text-secondary">
                        Order total
                    </h3>
                    <h3 className="text-base font-normal text-secondary">
                        {isNaN(orderTotal) ? formatPrice(0) : formatPrice(orderTotal)}
                    </h3>
                </div>
                <Button
                    onClick={handleSubmit}
                    className="w-full text-white bg-primary hover:bg-primary-dark hover:before:-translate-x-[420px]"
                    disabled={cartItems?.length < 1 || isDisabled}
                >
                    Order Now
                </Button>
            </div>
        </>
    );
};

export default CartSummary;
