<?php


namespace App\Interfaces\Context;


use App\DBContext\Model\FileCM;

interface IFile extends IDbContext
{
    /**
     * @param FileCM $file
     * @param string $giftItemID
     * @param array $options
     * @return FileCM
     */
    public function AddGiftItemGalleryImage($file, $giftItemID, $options = []);

    /**
     * @param FileCM $file
     * @param string $giftContainerID
     * @param array $options
     * @return FileCM
     */
    public function AddGiftContainerGalleryImage($file, $giftContainerID, $options = []);

    /**
     * @param FileCM $file
     * @param string $decorationID
     * @param array $options
     * @return FileCM
     */
    public function AddDecorationGalleryImage($file, $decorationID, $options = []);

    /**
     * @param string $fileID
     * @param string $giftItemID
     * @return FileCM
     */
    public function SetGiftItemMainImage($fileID, $giftItemID);

    /**
     * @param string $fileID
     * @param string $giftContainerID
     * @return FileCM
     */
    public function SetGiftContainerMainImage($fileID, $giftContainerID);

    /**
     * @param string $fileID
     * @param string $decorationID
     * @return FileCM
     */
    public function SetDecorationMainImage($fileID, $decorationID);

    /**
     * @param string $fileID
     * @param string $giftItemID
     * @return boolean
     */
    public function DeleteGiftItemGalleryImage($fileID, $giftItemID);

    /**
     * @param string $fileID
     * @param string $giftContainerID
     * @return boolean
     */
    public function DeleteGiftContainerGalleryImage($fileID, $giftContainerID);

    /**
     * @param string $fileID
     * @param string $decorationID
     * @return boolean
     */
    public function DeleteDecorationGalleryImage($fileID, $decorationID);

    /**
     * @param string $fileID
     * @param string $giftItemID
     * @return boolean
     */
    public function IsGiftItemMainImage($fileID, $giftItemID);

    /**
     * @param string $fileID
     * @param string $giftContainerID
     * @return boolean
     */
    public function IsGiftContainerMainImage($fileID, $giftContainerID);

    /**
     * @param string $fileID
     * @param string $decorationID
     * @return boolean
     */
    public function IsDecorationMainImage($fileID, $decorationID);

    /**
     * @param string $id Gift item ID.
     * @return FileCM[]
     */
    public function GetGiftItemGalleryImages($id);

    /**
     * @param string $id Gift container ID.
     * @return FileCM[]
     */
    public function GetGiftContainerGalleryImages($id);

    /**
     * @param string $id Decoration ID.
     * @return FileCM[]
     */
    public function GetDecorationGalleryImages($id);

    /**
     * @param string $id Decoration ID.
     * @return FileCM
     */
    public function GetGiftItemMainImage($id);

    /**
     * @param string $id Decoration ID.
     * @return FileCM
     */
    public function GetGiftContainerMainImage($id);

    /**
     * @param string $id Decoration ID.
     * @return FileCM
     */
    public function GetDecorationMainImage($id);
}