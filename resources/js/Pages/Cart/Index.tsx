import { Cart, PageProps, User } from "@/types";
import CartItem from "@/Pages/Cart/CartItem";
import CartSummary from "@/Pages/Cart/CartSummary";
import MainLayout from "@/Layouts/MainLayout";
import { router, useForm } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { toast } from "sonner";

const CartPage = ({ auth, cart }: PageProps<{ cart: Cart }>) => {
    const [isDisabled, setIsDisabled] = useState(false);

    const handleSubmit = () => {
        setIsDisabled(true);
        router.get(
            route("order.create"),
            undefined,
            {
                onFinish: () => {
                    setIsDisabled(false);
                },
            }
        );
    };

    return (
        <MainLayout user={auth.user}>
            <section className="px-8 py-8">
                <h1 className="mb-8 text-4xl font-bold leading-tight text-center text-primary">
                    Shopping Cart
                </h1>

                <div className="flex flex-col mt-12 gap-y-14 lg:flex-row gap-x-12">
                    {/* Cart Item */}
                    <div className="w-full space-y-5 lg:w-3/5">
                        {cart?.cart_items?.length === 0 ? (
                            <p className="text-secondary">
                                Your cart is empty.
                            </p>
                        ) : (
                            cart?.cart_items?.map((item) => (
                                <CartItem
                                    key={item.id}
                                    product={item.product}
                                />
                            ))
                        )}
                    </div>

                    {/* right */}
                    <div className="w-full lg:w-2/5">
                        <CartSummary
                            orderTotal={cart?.cart_items?.reduce(
                                (total, item) =>
                                    total + item.product.price * item.quantity,
                                0
                            )}
                            cartItems={cart?.cart_items}
                            handleSubmit={handleSubmit}
                            isDisabled={isDisabled}
                        />
                    </div>
                </div>
            </section>
        </MainLayout>
    );
};

export default CartPage;
