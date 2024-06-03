import Navbar from "@/Components/Navbar/Navbar";
import { Button } from "@/Components/ui/button";
import * as React from "react";

const CartPage = () => {
    return (
        <>
            <Navbar />
            <section className="px-8 py-16">
                <h1 className="mb-2 text-3xl font-bold leading-tight">
                    Shopping Cart
                </h1>

                <div className="flex mt-12 gap-x-12">
                    {/* left */}
                    <div className="w-3/5">
                        <div className="flex justify-between">
                            <div className="flex gap-x-6">
                                <div className="w-48 h-48 rounded-md">
                                    <img
                                        src="/images/product1.jpg"
                                        alt="product1"
                                        className="object-cover h-full rounded-md"
                                    />
                                </div>
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        Biji Air
                                    </h2>
                                    <h3 className="text-base">Rp500.000</h3>
                                </div>
                            </div>

                            <p className="text-sm text-primary/70">Clothing</p>

                            <div>
                                <div className="w-8 h-8 leading-8 text-center rounded-full shadow-md">
                                    X
                                </div>
                            </div>
                        </div>
                    </div>
                    {/* right */}
                    <div className="w-2/5">
                        <div className="p-8 rounded-md bg-gray-50">
                            <h2 className="mb-6 text-lg font-medium">
                                Order Summary
                            </h2>
                            <hr />
                            <div className="flex justify-between my-4">
                                <h3 className="text-base font-medium">
                                    Order total
                                </h3>
                                <h3 className="text-base font-normal">
                                    Rp500.000
                                </h3>
                            </div>
                            <Button className="w-full hover:before:-translate-x-[464px]">
                                Checkout
                            </Button>
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
};

export default CartPage;
