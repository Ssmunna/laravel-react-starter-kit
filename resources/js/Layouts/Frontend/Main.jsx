import '@/../css/client.css';
import {Head, usePage} from "@inertiajs/react";
import Notifier from "@/Components/Utils/Notification/Notifier.jsx";

const Main = ({children}) => {
    setTimeout(() => window.HSStaticMethods.autoInit(), 100)

    const {app_settings, fileBase} = usePage().props
    const favicon = app_settings['favicon'] ? JSON.parse(app_settings['favicon']['value']) : null;

    return (
        <div className="overflow-hidden bg-[#FFFFFF]">
            <Head>
                <link rel="icon" type="image/svg+xml" href={fileBase + '/' +favicon?.path} />
            </Head>
            <Notifier/>
            <div className="">
                {children}
            </div>
        </div>
    )
}

export default Main
