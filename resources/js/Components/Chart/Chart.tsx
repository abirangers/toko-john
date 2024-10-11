import { Bar, BarChart, CartesianGrid, XAxis } from "recharts";
import {
    ChartConfig,
    ChartContainer,
    ChartTooltip,
    ChartTooltipContent,
    ChartLegend,
    ChartLegendContent,
} from "@/Components/ui/chart";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { useState, useEffect } from "react";
import axios from "axios";

const chartConfig = {
    orders: {
        label: "Orders",
        color: "#2563eb",
    },
} satisfies ChartConfig;

export default function Chart({
    initialOrdersPerMonth,
    initialSelectedYear,
}: {
    initialOrdersPerMonth: { month: string; orders: number }[];
    initialSelectedYear: number;
}) {
    const [selectedYear, setSelectedYear] = useState(initialSelectedYear);
    const [ordersPerMonth, setOrdersPerMonth] = useState(initialOrdersPerMonth);

    useEffect(() => {
        const fetchData = async () => {
            const response = await axios.get("/api/dashboard", {
                params: { year: selectedYear },
            });
            setOrdersPerMonth(response.data.data);
        };

        fetchData();
    }, [selectedYear]);

    return (
        <div className="chart-wrapper">
            <div className="flex items-center justify-between mb-4 chart-header">
                <h2 className="text-xl font-semibold">Total Order</h2>
                <Select value={selectedYear} onValueChange={setSelectedYear}>
                    <SelectTrigger className="max-w-[200px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {[...Array(10)].map((_, i) => {
                            const yearOption = new Date().getFullYear() - i;
                            return (
                                <SelectItem key={yearOption} value={yearOption}>
                                    {yearOption}
                                </SelectItem>
                            );
                        })}
                    </SelectContent>
                </Select>
            </div>
            <ChartContainer
                config={chartConfig}
                className="min-h-[200px] w-full"
            >
                <BarChart accessibilityLayer data={ordersPerMonth}>
                    <CartesianGrid vertical={false} />
                    <XAxis
                        dataKey="month"
                        tickLine={false}
                        tickMargin={10}
                        axisLine={false}
                        tickFormatter={(value) => value.slice(0, 3)}
                    />
                    <ChartTooltip content={<ChartTooltipContent />} />
                    <ChartLegend content={<ChartLegendContent />} />
                    <Bar
                        dataKey="orders"
                        fill="var(--color-orders)"
                        radius={4}
                    />
                </BarChart>
            </ChartContainer>
        </div>
    );
}
