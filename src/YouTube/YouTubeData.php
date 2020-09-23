<?php

namespace VideoPublisherlite\YouTube;

class YouTubeData
{

    /**
     * Returns the YouTubeDataAPI instance.
     *
     * @return VideoPublisherlite\YouTube\YouTubeDataAPI
     */
    public static function api()
    {
        return new YouTubeDataAPI();
    }

}
