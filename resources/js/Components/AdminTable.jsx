export default function AdminTable({ children }) {
    return (
        <div className="hidden overflow-hidden rounded-2xl border border-border bg-card shadow-sm sm:block">
            <table className="min-w-full divide-y divide-border">
                {children}
            </table>
        </div>
    );
}

export function EmptyTableRow({ colSpan, children }) {
    return (
        <tr>
            <td colSpan={colSpan} className="px-6 py-4 text-sm text-muted-foreground">{children}</td>
        </tr>
    );
}
