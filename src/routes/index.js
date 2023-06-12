import { BrowserRouter, Routes, Route } from "react-router-dom";
import Players from "../pages/players";
import Stats from "../pages/stats";

const CustomRoutes = ({ children }) => {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<Players />} />
                <Route path="/stats" element={<Stats />} />
            </Routes>
        </BrowserRouter>
    )
}

export default CustomRoutes;