<?php

namespace App\Observers;

use App\Models\DirectoryMediaCover;

class DirectoryMediaCoverObserver
{
    /**
     * Handle the DirectoryMediaCover "created" event.
     *
     * @param  \App\Models\DirectoryMediaCover  $directoryMediaCover
     * @return void
     */
    public function created(DirectoryMediaCover $directoryMediaCover)
    {
        //
    }

    /**
     * Handle the DirectoryMediaCover "updated" event.
     *
     * @param  \App\Models\DirectoryMediaCover  $directoryMediaCover
     * @return void
     */
    public function updated(DirectoryMediaCover $directoryMediaCover)
    {
        if ($directoryMediaCover->isClean('position')) {
            return;
        }

        if (is_null($directoryMediaCover->position)) {
            $directoryMediaCover->position = DirectoryMediaCover::max('position');
        }

        if ($directoryMediaCover->getOriginal('position') > $directoryMediaCover->position) {
            $positionRange = [
                $directoryMediaCover->position, $directoryMediaCover->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $directoryMediaCover->getOriginal('position'), $directoryMediaCover->position
            ];
        }

        $lowerPriorityCategories = DirectoryMediaCover::where('id', '!=', $directoryMediaCover->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityCategories as $lowerPriorityCategory) {
            if ($directoryMediaCover->getOriginal('position') < $directoryMediaCover->position) {
                $lowerPriorityCategory->position--;
            } else {
                $lowerPriorityCategory->position++;
            }
            $lowerPriorityCategory->saveQuietly();
        }
    }

    /**
     * Handle the DirectoryMediaCover "deleted" event.
     *
     * @param  \App\Models\DirectoryMediaCover  $directoryMediaCover
     * @return void
     */
    public function deleted(DirectoryMediaCover $directoryMediaCover)
    {
        //
    }

    /**
     * Handle the DirectoryMediaCover "restored" event.
     *
     * @param  \App\Models\DirectoryMediaCover  $directoryMediaCover
     * @return void
     */
    public function restored(DirectoryMediaCover $directoryMediaCover)
    {
        //
    }

    /**
     * Handle the DirectoryMediaCover "force deleted" event.
     *
     * @param  \App\Models\DirectoryMediaCover  $directoryMediaCover
     * @return void
     */
    public function forceDeleted(DirectoryMediaCover $directoryMediaCover)
    {
        //
    }
}
