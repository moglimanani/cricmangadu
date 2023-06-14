import { initialState } from "./initialState";

const playersReducer = (state = { ...initialState.players }, action) => {
    switch (action.type) {
        default: {
            return { ...state };
        }
    }
}

export default playersReducer;