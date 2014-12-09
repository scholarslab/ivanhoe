require 'spec_helper'

describe 'Make a Game View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        click_link('Make a Game')
    end

    it 'has the WP Editor' do
      expect(page).to have_selector('.wp-editor-container')
    end

    it 'has the Make a Game header' do
      expect(page).to have_content('Make a Game')
    end

    it 'has a game title label' do
      expect(page).to have_content('Game Title')
    end

    it 'has a required game title field' do
      expect(page).to have_field('post_title', :type => 'text')
      click_button 'Save'
      expect(page).to have_content('A title is required')
    end

    it 'has a game thumbnail label' do
      expect(page).to have_content('Game Thumbnail')
    end

    it 'has a game thumbnail input' do
      expect(page).to have_field('post_thumbnail', :type => 'file')
    end

    it 'has a game description label' do
      expect(page).to have_content('Game Description')
    end

    it 'has the Add Media button' do
      expect(page).to have_selector('#insert-media-button')
    end

    it 'has a required game description input' do
      expect(page).to have_selector('#wp-post_content-wrap')
      # not sure if this is the best selector for this
      click_button 'Save'
      expect(page).to have_content('A description is required')
    end

    it 'has a save button' do
      expect(page).to have_selector('input[type=submit][value=Save]')
    end

end
