import DashboardLayout from "@/Layouts/DashboardLayout";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Pencil } from "lucide-react";
import { Product } from "@/types";
import { formatPrice } from "@/lib/utils";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ShowProduct({ product }: { product: Product }) {
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Product</h1>
                    <p className="text-sm text-muted-foreground">
                        {product.title}
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() =>
                        router.get(route("admin.products.edit", product.id))
                    }
                >
                    <Pencil className="w-3 h-3 mr-2" />
                    Edit
                </Button>
            </div>
            <div className="mt-4 text-secondary">
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Product Name
                        </label>
                        <input
                            type="text"
                            value={product.title}
                            disabled
                            className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Category
                        </label>
                        <input
                            type="text"
                            value={product.category.name}
                            disabled
                            className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Price
                        </label>
                        <input
                            type="text"
                            value={formatPrice(product.price)}
                            disabled
                            className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Stock
                        </label>
                        <input
                            type="text"
                            value={product.stock}
                            disabled
                            className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div className="col-span-1 md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            value={product.description}
                            disabled
                            rows={4}
                            className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    {/* image nya */}
                    <div className="col-span-1 md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <div className="mt-2 overflow-hidden border border-gray-300 rounded-lg">
                            <img
                                src={product.image}
                                alt="Image Preview"
                                className="object-cover w-full h-auto aspect-video"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
