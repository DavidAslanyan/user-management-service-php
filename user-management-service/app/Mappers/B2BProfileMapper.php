<?php

namespace App\Mappers;

use App\Models\B2BProfile;
use App\Models\B2BProfileEntity;
use App\Mappers\UserMapper;

class B2bProfileMapper
{
    public static function toModel(B2BProfileEntity $entity): B2BProfile
    {
        return new B2BProfile(
            UserMapper::toModel($entity->user),
            $entity->profile_type,
            $entity->profile_status,
            $entity->profile_role,
            $entity->follower_count,
            $entity->website,
            $entity->youtube_links,
            $entity->address,
            $entity->followers->map(fn($f) => $f->id),
            $entity->account_image_path,
            $entity->profile_image_path,
            $entity->legal_name,
            $entity->venue_name,
            $entity->stage_name,
            $entity->genres,
            $entity->facebook,
            $entity->instagram,
            $entity->linkedin,
            $entity->portfolio_images,
            $entity->portfolio_videos,
            $entity->cover_photo_path,
            $entity->bio,
            $entity->rejected_reason,
            $entity->id,
            $entity->created_at,
            $entity->updated_at
        );
    }

    public static function toEntity(B2BProfile $model): B2BProfileEntity
    {
        $entity = new B2BProfileEntity();
        $entity->id = $model->getId();
        $entity->user = UserMapper::toEntity($model->getUser());
        $entity->profile_type = $model->getProfileType();
        $entity->profile_role = $model->getProfileRole();
        $entity->website = $model->getWebsite();
        $entity->youtube_links = $model->getYoutubeLinks();
        $entity->profile_status = $model->getProfileStatus();
        $entity->address = $model->getAddress();
        $entity->created_at = $model->getCreatedAt();
        $entity->account_image_path = $model->getAccountImagePath();
        $entity->profile_image_path = $model->getProfileImagePath();
        $entity->legal_name = $model->getLegalName();
        $entity->venue_name = $model->getVenueName();
        $entity->stage_name = $model->getStageName();
        $entity->genres = $model->getGenres();
        $entity->facebook = $model->getFacebook();
        $entity->instagram = $model->getInstagram();
        $entity->linkedin = $model->getLinkedin();
        $entity->portfolio_images = $model->getPortfolioImages();
        $entity->portfolio_videos = $model->getPortfolioVideos();
        $entity->cover_photo_path = $model->getCoverPhotoPath();
        $entity->bio = $model->getBio();
        $entity->rejected_reason = $model->getRejectedReason();
        $entity->follower_count = $model->getFollowerCount();

        return $entity;
    }
}
