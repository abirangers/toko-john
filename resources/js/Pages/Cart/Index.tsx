import { CartItem as CartItemType, PageProps } from "@/types";
import CartItem from "@/Pages/Cart/CartItem";
import OrderSummary from "@/Pages/Cart/OrderSummary";
import MainLayout from "@/Layouts/MainLayout";
import { router, useForm } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { toast } from "sonner";

const CartPage = ({
    auth,
    cartItems,
}: PageProps<{ cartItems: CartItemType[] }>) => {
    const [isOpen, setIsOpen] = useState(false);
    const [isOrderSuccessful, setIsOrderSuccessful] = useState(false);
    const form = useForm({ cartItems });

    useEffect(() => {
        if (form.errors) {
            Object.values(form.errors).forEach((error: any) => {
                toast.error(error);
            });
        }
    }, [form.errors]);

    const handleSubmit = (
        e: React.MouseEvent<HTMLButtonElement, MouseEvent>
    ) => {
        e.preventDefault();

        if (!auth.user) {
            router.visit("/login");
            return;
        }

        try {
            form.post(route("cart.store"), {
                onSuccess: async (params) => {
                    const flash = params.props.flash as {
                        error: string;
                    };

                    if (flash.error) {
                        toast.error(flash.error);
                        return;
                    }

                    setIsOrderSuccessful(true);
                    setIsOpen(true);
                },
            });
        } catch (e) {
            toast.error("Failed to send cart to server");
            console.log(e);
        }
    };

    useEffect(() => {
        if (!isOpen && isOrderSuccessful) {
            router.visit(route("order.index"));
        }
    }, [isOpen, isOrderSuccessful]);

    return (
        <MainLayout user={auth.user}>
            <section className="px-8 py-16">
                <h1 className="mb-2 text-3xl font-bold leading-tight">
                    Shopping Cart
                </h1>

                <div className="flex flex-col mt-12 gap-y-14 lg:flex-row gap-x-12">
                    {/* Cart Item */}
                    <div className="w-full space-y-5 lg:w-3/5">
                        {cartItems.length === 0 ? (
                            <p>Your cart is empty.</p>
                        ) : (
                            cartItems.map((item) => (
                                <CartItem
                                    key={item.id}
                                    product={item.product}
                                />
                            ))
                        )}
                    </div>

                    {/* right */}
                    <div className="w-full lg:w-2/5">
                        <OrderSummary
                            orderTotal={cartItems.reduce(
                                (total, item) =>
                                    total + item.product.price * item.quantity,
                                0
                            )}
                            cartItems={cartItems}
                            form={form}
                            handleSubmit={handleSubmit}
                            isOpen={isOpen}
                            setIsOpen={setIsOpen}
                        />
                    </div>
                </div>
            </section>
        </MainLayout>
    );
};

export default CartPage;
