import { cn } from "@/lib/utils";
import { router, usePage } from "@inertiajs/react";
import { Separator } from "@radix-ui/react-dropdown-menu";
import { Tabs, TabsList, TabsTrigger } from "@radix-ui/react-tabs";
import React, { useEffect, useState } from "react";

/**
 * Renders the order tabs component.
 *
 * @returns {React.JSX.Element} The rendered order tabs component.
 */
const OrderTabs = (): React.JSX.Element => {
    const { orderStatusParams } = usePage().props;

    const tabs = [
        {
            title: "All",
            href: "/orders",
            isActive: orderStatusParams == null,
        },
        {
            title: "Pending",
            href: "/orders?status=pending",
            isActive: orderStatusParams == "pending",
        },
        {
            title: "Paid",
            href: "/orders?status=paid",
            isActive: orderStatusParams == "paid",
        },
        {
            title: "Cancelled",
            href: "/orders?status=cancelled",
            isActive: orderStatusParams == "cancelled",
        },
    ];

    const activeTab = tabs.find((find) => find.isActive) ?? tabs[0];

    return (
        <Tabs
            className="w-full px-1 bg-background"
            defaultValue={activeTab.href}
            onValueChange={(value) => router.get(value)}
        >
            <TabsList className="inline-flex w-full items-center justify-between space-x-1.5 text-muted-foreground">
                {tabs.map((tab, i) => (
                    <TabsItem key={i} tab={tab}/>
                ))}
            </TabsList>
        </Tabs>
    );
};

/**
 * Renders a single tab item.
 *
 * @param {Object} props - The props object containing the tab information.
 * @param {string} props.tab.title - The title of the tab.
 * @param {string} props.tab.href - The URL of the tab.
 * @param {boolean} props.tab.isActive - Indicates if the tab is active.
 * @returns {React.JSX.Element} The rendered tab item.
 */
const TabsItem = ({tab}: {tab: {title: string; href: string; isActive: boolean}}): React.JSX.Element => {
    return (
        <div
            role="none"
            className={cn(
                "w-full border-b-2 border-transparent py-1.5 text-sm font-medium text-muted-foreground hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1",
                {
                    "border-primary": tab.isActive,
                }
            )}
        >
            <TabsTrigger
                value={tab.href}
                className={cn(
                    "w-full inline-flex items-center justify-center rounded-sm px-3 py-1.5 text-sm font-medium text-muted-foreground ring-offset-background transition-all hover:bg-muted hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1",
                    {
                        "text-primary": tab.isActive,
                    }
                )}
            >
                {tab.title}
            </TabsTrigger>
        </div>
    )
}

export default OrderTabs;

