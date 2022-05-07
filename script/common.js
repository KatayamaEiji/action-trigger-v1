
export class ACTION_LOG {
    static ACTION_STATE_VERIFICATION = 9; // 確認
    static ACTION_STATE_READY = 0;
    static ACTION_STATE_START = 1;
    static ACTION_STATE_STOP = 2;
    static ACTION_STATE_COMPLETE = 3;

    static consoleLogActionState(actionStatus) {
        switch (actionStatus) {
            case ACTION_LOG.ACTION_STATE_VERIFICATION:
                console.log("ACTION_STATE_VERIFICATION");
                break;
            case ACTION_LOG.ACTION_STATE_READY:
                console.log("ACTION_STATE_READY");
                break;
            case ACTION_LOG.ACTION_STATE_START:
                console.log("ACTION_STATE_START");
                break;
            case ACTION_LOG.ACTION_STATE_STOP:
                console.log("ACTION_STATE_STOP");
                break;
            case ACTION_LOG.ACTION_STATE_COMPLETE:
                console.log("ACTION_STATE_COMPLETE");
                break;
        }
    }
}

export class ACTION {
    static ACTION_TYPE_COUNT_UP = 1; // 確認
    static ACTION_TYPE_COUNT_DOWN = 2;
    static ACTION_TYPE_FIRST = 3;
    static ACTION_TYPE_YOUTUBE = 4;
}